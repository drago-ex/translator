<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Localization;

use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Presenter;
use Nette\Neon\Exception;


/**
 * Presenter helper for translator integration.
 *
 * Handles persistent language parameter
 * and template translator setup.
 */
trait TranslatorAdapter
{
	#[Persistent]
	public string $lang;

	public Translator $translator;

	private bool $translatorInitialized = false;


	/**
	 * @param Translator $translator Translator service
	 * @param Presenter  $presenter  Target presenter
	 */
	public function injectTranslator(Translator $translator, Presenter $presenter): void
	{
		$this->translator = $translator;

		$presenter->onRender[] = function () use ($presenter) {
			$presenter->template->lang = $this->lang;
			$presenter->template->setTranslator($this->getTranslator());
		};
	}


	/**
	 * Returns initialized translator for current language.
	 * @throws Exception
	 */
	public function getTranslator(): Translator
	{
		if (!$this->translatorInitialized) {
			$this->translator->setTranslate($this->lang);
			$this->translatorInitialized = true;
		}

		return $this->translator;
	}
}
