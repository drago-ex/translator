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
trait Locales
{
	/**
	 * @persistent
	 * @var string
	 */
	public $lang;

	/**
	 * @param string
	 * @return Translator
	 */
	public function getTranslator($filename)
	{
		return new Translator($filename);
	}

}
