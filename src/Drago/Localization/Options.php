<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Localization;


/**
 * Translator configuration options.
 *
 * Used by the DI extension.
 */
class Options
{
	/** Optional path to module translations. */
	public ?string $moduleLocaleDir = null;
}
