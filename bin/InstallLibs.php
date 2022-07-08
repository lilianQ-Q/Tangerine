<?php
	$data = file_get_contents(__DIR__ . "/../config/tinypm.json");
	$data = json_decode($data);
	if (!isset($data->libs) || empty($data->libs))
		return ;
	
	deleteLibs();

	foreach ($data->libs as $library)
	{
		install($library->address, $library->name);
	}

	function install(string $url, string $name)
	{
		echo "Retrieving library $url ...\n";
		exec("git clone $url " . __DIR__ . "/../libs/$name");
	}

	function deleteLibs()
	{
		exec("rm -rf " . __DIR__ . "/../libs/");
	}
?>