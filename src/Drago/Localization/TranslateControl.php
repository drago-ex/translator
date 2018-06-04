<?php

/**
 * Drago Translator
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Localization;

/**
 * Simple translator.
 */
trait TranslateControl
{
	/**
	 * @var Translator
	 */
	public $translation;

	/**
	 * @param array $translation
	 */
	public function setTranslator($translation)
	{
		$this->translation = $translation;
	}

}
