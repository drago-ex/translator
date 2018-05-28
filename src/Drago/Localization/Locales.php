<?php

// Enable strict mode.
declare(strict_types = 1);

/**
 * Drago Translator
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Localization;
use Drago\Localization\Translator;

/**
 * Simple translator.
 */
trait Locales
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
