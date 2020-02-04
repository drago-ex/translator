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
trait TranslatorAdapter
{
	/**
	 * @var string
	 * @persistent
	 */
	public $lang;

	/** @var Translator */
	public $translator;


	public function injectTranslator(Translator $translator): void
	{
		$this->translator = $translator;
	}


	/**
	 * @throws \Exception
	 */
	public function getTranslator(): Translator
	{
		$this->translator->setTranslate($this->lang);
		return $this->translator;
	}
}
