<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Localization;

use Nette\Application\UI\Presenter;


/**
 * Simple translator.
 */
trait TranslatorAdapter
{
	/** @persistent */
	public string $lang;

	private Translator $translator;


	public function injectTranslator(Translator $translator, Presenter $presenter): void
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
