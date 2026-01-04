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
 * Trait for presenters to provide translation functionality.
 * Handles language parameter and injects the Translator instance.
 */
trait TranslatorAdapter
{
	/** @var string Language code from route (persistent) */
	#[Persistent]
	public string $lang;

	/** @var Translator Translator instance used for translations */
	public Translator $translator;

	/** @var bool Ensures translations are initialized only once per request */
	private bool $translatorInitialized = false;


	/**
	 * Injects the Translator and sets up template integration.
	 *
	 * @param Translator $translator Translator service
	 * @param Presenter $presenter Presenter to inject translator into
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
	 * Returns the Translator instance with merged translations for the current language.
	 *
	 * @return Translator
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
