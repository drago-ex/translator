<?php

declare(strict_types = 1);

use Drago\Localization\TranslateControl;
use Drago\Localization\Translator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

class Control
{
	use TranslateControl;
}

$translator = new Translator;
$translator->setFile(__DIR__ . '/en');

$control = new Control;
$control->setTranslator($translator);

Assert::type(Translator::class, $control->getTranslator());
