<?php

declare(strict_types = 1);

/**
 * Drago Localization
 * Package built on Nette Framework
 */

namespace Drago\Localization;


/**
 * Simple translator.
 * @package Drago\Localization
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
