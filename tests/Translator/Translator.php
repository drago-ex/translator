<?php

declare(strict_types = 1);

use Drago\Localization\Translator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$translator = new Translator;
$translator->setFile(__DIR__ . '/en');

Assert::type('string', $translator->translate('hello.world'));
Assert::same('Hello, world!', $translator->translate('hello.world'));
