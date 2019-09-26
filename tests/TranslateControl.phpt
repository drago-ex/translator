<?php

declare(strict_types = 1);

use Drago\Localization\TranslateControl;
use Drago\Localization\Translator;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

class Control
{
	use TranslateControl;
}

$control = new Control();
$control->setTranslator(new Translator(__DIR__ . '/locale/en.ini'));

Assert::type('object', $control->getTranslator());
