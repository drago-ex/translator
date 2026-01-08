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
	/**
	 * Enables automatic scanning of all translation files in the app directory.
	 * If false, translator will use only the manually specified translateDir.
	 */
	public bool $autoFinder = true;

	/**
	 * Custom translation directory.
	 * Must be set if autoFinder is false.
	 *
	 * @var string[]
	 */
	public array $translateDirs = [];
}
