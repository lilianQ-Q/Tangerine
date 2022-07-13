<?php
namespace Tangerine;

use Tangerine\exceptions\MissingDataException;

class Engine
{
	public array $blocks = array();
	public array $data = array();
	private array $phpVariables = array();
	private array $echos = array();
	public string $cachePath;
	public string $viewsFolder;
	public bool $cacheEnabled;

	public function __construct(string $cachePath, string $viewFolder, bool $cacheEnabled = false)
	{
		$this->cachePath = $cachePath;
		$this->viewsFolder = $viewFolder;
		$this->cacheEnabled = $cacheEnabled;
	}

	/**
	 * Renders a new view by requiring it
	 * 
	 * @param $fileName Name of the view to render
	 * @param $data Array of data to pass to the view
	 * @return string
	 */
	public function render(string $fileName, $data = array()) : string
	{
		$this->data = $data;
		$cachedFile = $this->cache($fileName);
		extract($this->data, EXTR_SKIP);

		ob_start();
		require($cachedFile);
		$result = ob_get_clean();

		if (!$this->cacheEnabled)
			$this->clearCache();

		return ($result);
	}

	/**
	 * Returns a path to the new created cache File
	 * 
	 * @param string $file Name of the file to cache
	 * @return string
	 */
	private function cache(string $file) : string
	{
		if (!file_exists($this->cachePath))
			mkdir($this->cachePath, 0744);
		
		$cachedFile = "{$this->cachePath}/" . str_replace(array('/', '.html'), array('_', ''), $file . '.php');
		if (!$this->cacheEnabled || !file_exists($cachedFile) || filemtime($cachedFile) < filemtime("{$this->viewsFolder}/$file"))
		{
			$code = $this->includeFiles($file);
			$code = $this->compileCode($code);
			$code = '<?php class_exists(\'' . __CLASS__ . '\') or exit(); ?>' . PHP_EOL . $code;
			file_put_contents($cachedFile, $code);
		}
		return ($cachedFile);
	}

	/**
	 * Clears cache folder
	 * 
	 * @param void
	 * @return void
	 */
	public function clearCache() : void
	{
		foreach (glob($this->cachePath . '/*') as $file)
		{
			unlink($file);
		}
	}

	/**
	 * Returns the compiled code as a string
	 * 
	 * @param string $code Based php code to compile
	 * @return string
	 */
	private function compileCode(string $code) : string
	{
		$code = $this->compileBlock($code);
		$code = $this->compileYield($code);
		$code = $this->compilePHP($code);
		$code = $this->compileEscapedEchos($code);
		$code = $this->compileEchos($code);
		$code = $this->checkVariables($code);
		return ($code);
	}

	/**
	 * Returns the compiled code as a string after including files
	 * 
	 * @param string $file Name of the files to include
	 * @return string Compiled code
	 */
	private function includeFiles(string $file) : string
	{
		$code = file_get_contents($this->viewsFolder . "/$file");
		preg_match_all('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
		foreach ($matches as $value)
		{
			$code = str_replace($value[0], $this->includeFiles($value[2]), $code);
		}
		$code = preg_replace('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', '', $code);
		return ($code);
	}

	/**
	 * Returns the compiled code after compiling php sources
	 * 
	 * @param string $code Code to compile
	 * @return string Compiled code
	 */
	private function compilePhp(string $code) : string
	{
		preg_match('~\{%\s*(.+?)\s*\%}~is', $code, $matches);
		if (!empty($matches))
		{
			preg_match_all('/\$([\w]+)/', $matches[1], $variables);
			$this->phpVariables = array_merge($this->phpVariables, $variables[1]);
		}
		return (preg_replace('~\{%\s*(.+?)\s*\%}~is', '<?php $1 ?>', $code));
	}

	private function checkVariables(string $code) : string
	{
		
		return ($code);
	}

	/**
	 * Returns the compiled code as a string after including files
	 * 
	 * @param string $file Code to compile
	 * @return string $code Compiled code
	 */
	private function compileEchos(string $code)
	{
		preg_match_all('/{{\s*\$([\w]+\s*)}}/is', $code, $matches);
		if (!empty($matches[0]))
		{
			foreach ($matches[1] as $varName)
			{
				$varName = trim($varName, ' ');
				if (!array_key_exists($varName, $this->data) && !in_array("$varName", $this->phpVariables))
					throw new MissingDataException(sprintf("%s variable is not passed to the engine when loading template.", $varName));
			}
		}
		return (preg_replace('~\{{\s*(.+?)\s*\}}~is', '<?php echo $1 ?>', $code));
	}

	/**
	 * Returns the compiled code as a string after compile echos that need to be escaped
	 * 
	 * @param string $code Code to compile
	 * @return string Compiled code
	 */
	private function compileEscapedEchos(string $code)
	{
		return (preg_replace('~\{{{\s*(.+?)\s*\}}}~is', '<?php echo htmlentities($1, ENT_QUOTES, \'UTF-8\') ?>', $code));
	}

	/**
	 * Returns the compiled code as a string after compile blocks
	 * 
	 * @param string $code Code to compile
	 * @return string Compiled code
	 */
	private function compileBlock(string $code)
	{
		preg_match_all('/{% ?block ?(.*?) ?%}(.*?){% ?endblock ?%}/is', $code, $matches, PREG_SET_ORDER);
		foreach ($matches as $value)
		{
			if (!array_key_exists($value[1], $this->blocks))
				$this->blocks[$value[1]] = '';
			if (!strpos($value[2], '@parent'))
				$this->blocks[$value[1]] = $value[2];
			else
				$this->blocks[$value[1]] = str_replace('@parent', $this->blocks[$value[1]], $value[2]);
			$code = str_replace($value[0], '', $code);
		}
		return ($code);
	}

	/**
	 * Returns the compiled code as a string after compile yields
	 * 
	 * @param string $code Code to compile
	 * @return string Compiled code
	 */
	private function compileYield(string $code)
	{
		foreach ($this->blocks as $block => $value)
		{
			$code = preg_replace('/{% ?yield ?' . $block . ' ?%}/', $value, $code);
		}
		$code = preg_replace('/{% ?yield ?(.*?) ?%}/i', '', $code);
		return ($code);
	}
}

?>