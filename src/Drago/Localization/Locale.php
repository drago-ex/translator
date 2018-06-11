<?php

/**
 * Drago Translator
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Localization;
use Drago\Localization\Translator;

/**
 * Simple translator.
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
	 * @param string $filename
	 * @return Translator
	 */
	public function createTranslator($filename)
	{
		return new Translator($filename);
	}

}
