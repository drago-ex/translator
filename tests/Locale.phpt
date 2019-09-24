<?php

declare(strict_types = 1);

use Drago\Localization\Locale;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

class Localization {
	use Locale;
}

$locale = new Localization();
$locale->lang = 'en';

Assert::type('object', $locale->createTranslator(__DIR__ . '/locale/' . $locale->lang . '.ini'));
