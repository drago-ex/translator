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
trait TranslateControl
{
	/**
	 * @var Translator
	 */
	public $translator;


	public function setTranslator(Translator $translator)
	{
		$this->translator = $translator;
	}
}
