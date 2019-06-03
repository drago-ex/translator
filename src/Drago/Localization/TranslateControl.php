<?php declare(strict_types = 1);

/**
 * Drago Localization
 * Package built on Nette Framework
 */
namespace Drago\Localization;

/**
 * Simple translator.
 * @package Drago\Localization
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
