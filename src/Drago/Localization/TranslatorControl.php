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
trait TranslatorControl
{
	/** @var Translator */
	private $translator;


	public function setTranslator(Translator $translator): void
	{
		$this->translator = $translator;
	}


	public function getTranslator(): Translator
	{
		return $this->translator;
	}
}
