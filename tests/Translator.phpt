<?php

declare(strict_types = 1);

use Drago\Localization\Translator;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

$translator  = new Translator(__DIR__ . '/locale/en.ini');

Assert::type('string', $translator->translate('hello.world'));
Assert::same('Hello, world!', $translator->translate('hello.world'));
Assert::same('Test', $translator->translate('Test'));
Assert::exception(function() {
	new Translator('');
}, Exception::class, 'The translation file was not found.');
