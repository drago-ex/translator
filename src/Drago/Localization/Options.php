<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Localization;


/**
 * Translator options.
 *
 * Holds optional module-specific translation directory.
 */
class Options
{
	/** @var string|null Optional path to module-specific translations */
	public ?string $moduleDir = null;
}
