<?php

declare(strict_types = 1);

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


	public function setTranslator(array $translation)
	{
		$this->translation = $translation;
	}
}
