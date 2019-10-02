<?php

declare(strict_types = 1);

/**
 * Drago Localization
 * Package built on Nette Framework
 */

namespace Drago\Localization;


/**
 * Simple translator.
 */
trait TranslateControl
{
	/** @var Translator */
	private $translator;


	public function setTranslator(Translator $translator)
	{
		$this->translator = $translator;
	}


	public function getTranslator(): Translator
	{
		return $this->translator;
	}
}
