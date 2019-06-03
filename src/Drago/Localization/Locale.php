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
	 * @persistent
	 * @var string
	 */
	public $lang;


	/**
	 * Create a translation.
	 */
	public function createTranslator(string $filename): Translator
	{
		return new Translator($filename);
	}
}
