<?php

declare(strict_types = 1);

use Drago\Localization\Locale;
use Drago\Localization\Translator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

class Localization
{
	use Locale;
}

$locale = new Localization;
$locale->translator = new Translator;
$locale->lang = 'en';

Assert::type(Translator::class, $locale->createTranslator(__DIR__ . '/' . $locale->lang));
