<?php

declare(strict_types = 1);

use Drago\Localization\TranslateControl;
use Drago\Localization\Translator;
use Drago\Localization\Locale;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

$translator  = new Translator(__DIR__ . '/locale/en.ini');

Assert::type('string', $translator->translate('hello.world'));

class Control {
	use TranslateControl;
}

$control = new Control();
$control->setTranslator($translator);

Assert::type('object', $control->getTranslator());
Assert::same('Hello, world!', $control->getTranslator()->translate('hello.world'));

class Localization {
	use Locale;
}

$locale = new Localization();
$locale->lang = 'en';

Assert::exception(function() use ($locale) {
	$locale->createTranslator('');
}, Exception::class, 'The translation file was not found.');


Assert::type('object', $locale->createTranslator(__DIR__ . '/locale/' . $locale->lang . '.ini'));
