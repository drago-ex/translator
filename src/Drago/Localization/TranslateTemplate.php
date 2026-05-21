<?php

declare(strict_types=1);

namespace Drago\Localization;

use Nette\Bridges\ApplicationLatte\Template;


/** Custom template class with language property for better IDE and PHPStan support. */
class TranslateTemplate extends Template
{
	public string $lang;
}
