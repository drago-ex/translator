<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Localization;

use Nette;


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
	private $translator;


	public function injectTranslator(Translator $translator, Nette\Application\UI\Presenter $presenter): void
	{
		$this->translator = $translator;
		$presenter->onRender[] = function () use ($presenter) {
			$presenter->template->lang = $this->lang;
			$presenter->template->setTranslator($this->getTranslator());
		};
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
