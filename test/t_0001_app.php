<?php

namespace TangerineTests;

use Everest\core\entities\TestCase;
use Tangerine\Engine;
use Tangerine\exceptions\MissingDataException;
use Tangerine\exceptions\YieldException;

class t_0001_app extends TestCase
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Check if the cached file is deleted in the cache folder after rendering it.
	 */
	public function TestCacheDisabled()
	{
		$engine = new Engine(__DIR__ . "/../cached", __DIR__ . "/../views");
		ob_start();
		$engine->render("index.html");
		ob_get_clean();
		$this->assertNotFileExists(__DIR__ . "/../cached/index.php");
	}

	/**
	 * Check if the cached file is kept in cache folder after rendering it.
	 */
	public function TestCacheEnabled()
	{
		$engine = new Engine(__DIR__ . "/../cached", __DIR__ . "/../views", true);
		ob_start();
		$engine->render("index.html");
		ob_get_clean();
		$this->assertFileExists(__DIR__ . "/../cached/index.php");
	}

	public function TestRenderedFileIsWhatWasExpected()
	{
		$engine = new Engine(__DIR__ . "/../cached", __DIR__ . "/../views/testviews/01");
		$page = $engine->render("test_index.html");
		$page = str_replace(["\n", "\r", "\t", ' '], '', $page);
		$this->assertNotEmpty($page);
		$this->assertSame($page, '<!DOCTYPEhtml><html><head><title>HomePage</title><metacharset="utf-8"></head><body><h1>Home</h1><p>Welcometothehomepage!</p>pute</body></html>');
	}

	public function TestRenderedFileIsNotWhatWasExpected()
	{
		$engine = new Engine(__DIR__ . "/../cached", __DIR__ . "/../views/testviews/01");
		$page = $engine->render("test_index.html");
		$page = str_replace(["\n", "\r", "\t", ' '], '', $page);
		$this->assertNotEquals($page, '<!DOCTYPEhtml><html><head><title>HomePage</title><metacharset="utf-8"></head><body><h1>Home</h1><p>h3ph435t05</p></body></html>');
	}

	public function TestIfDataHasBeenTransmitted()
	{
		$engine = new Engine(__DIR__ . "/../cached", __DIR__ . "/../views/testviews/02");
		$page = $engine->render("test_index.html", ["fruits" => ["apple", "banana", "kiwi"]]);
		$page = str_replace(["\n", "\r", "\t", ' '], '', $page);
		$this->assertEquals($page, "<ul><li>apple</li><li>banana</li><li>kiwi</li></ul>");
	}

	public function TestMissingData()
	{
		try 
		{
			$engine = new Engine(__DIR__ . "/../cached", __DIR__ . "/../views/testviews/03");
			$page = $engine->render("test_index.html", ["fruits" => ["apple", "banana", "kiwi"]]);
		}
		catch (\Exception $exception)
		{
			$this->assertTrue($exception instanceof MissingDataException);
		}
	}
}

?>