<?php declare(strict_types=1);

use Nette\Bootstrap\Configurator;

if (@!include __DIR__ . '/../vendor/autoload.php') { // @ dependencies may not be installed
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}


Tester\Environment::setup();
Tester\Environment::setupFunctions();
date_default_timezone_set('Europe/Prague');

const TempDir = __DIR__ . '/tmp';

@mkdir(dirname(TempDir)); // @ directory may already exist
@mkdir(TempDir); // @ directory may already exist

$boot = new Configurator;
$boot->setTempDirectory(TempDir);
$boot->createRobotLoader()
	->addDirectory(__DIR__)
	->addDirectory(__DIR__ . '/../src')
	->register();

return $boot->createContainer();
