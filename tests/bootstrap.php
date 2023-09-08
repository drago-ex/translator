<?php

declare(strict_types=1);

use Nette\Bootstrap\Configurator;

if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}


Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

const TemDir = __DIR__ . '/tmp';

@mkdir(dirname(TemDir));
@mkdir(TemDir);

$boot = new Configurator();
$boot->setTempDirectory(TemDir);
$boot->createRobotLoader()
	->addDirectory(__DIR__)
	->addDirectory(__DIR__ . '/../src')
	->register();

return $boot->createContainer();
