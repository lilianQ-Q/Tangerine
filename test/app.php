<?php

use Everest\Tester;
use Spatie\Ignition\Ignition;
use Tangerine\Engine;

require_once(__DIR__ . '/../libs/PhpAutoloader/autoloader.php');

$tester = Tester::instance();
$tester->directory("TangerineTests", __DIR__)
        ->run();
?>