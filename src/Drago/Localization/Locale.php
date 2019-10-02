<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Localization;


/**
 * Simple translator.
 */
trait Locale
{
	/**
	 * @var string
	 * @persistent
	 */
	public $lang;


	/**
	 * Create a translation.
	 * @param  string  $filename
	 * @throws \Exception
	 */
	public function createTranslator(string $filename): Translator
	{
		return new Translator($filename);
	}
}
