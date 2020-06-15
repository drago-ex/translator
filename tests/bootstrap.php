<?php

declare(strict_types = 1);

use Nette\Configurator;
use Tester\Environment;

require __DIR__ . '/../vendor/autoload.php';

Environment::setup();
date_default_timezone_set('Europe/Prague');
define('TEMP_DIR', __DIR__ . '/tmp');

$boot = new Configurator;
$boot->setTempDirectory(TEMP_DIR);
$boot->createRobotLoader()
	->addDirectory(__DIR__)
	->addDirectory(__DIR__ . '/../src')
	->register();

return $boot->createContainer();
