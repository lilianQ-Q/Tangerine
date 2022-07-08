<?php

use Tangerine\Engine;

require_once(__DIR__ . '/../libs/PhpAutoloader/autoloader.php');

$engine = new Engine(__DIR__ . "/../cached", __DIR__ . "/../views");
$engine->render("index.html");
?>