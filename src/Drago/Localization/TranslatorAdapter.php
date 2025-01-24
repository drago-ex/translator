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
 * TranslatorAdapter trait provides translation functionality to presenters
 * by injecting a Translator instance and handling the language setting.
 */
trait TranslatorAdapter
{
	/**
	 * @var string The selected language for translation.
	 * The language is persisted across requests.
	 */
	#[Persistent]
	public string $lang;

	/** @var Translator The Translator instance responsible for translating messages. */
	public Translator $translator;


	/**
	 * Injects the Translator instance and sets up the onRender callback
	 * to pass the language and translator to the presenter template.
	 *
	 * @param Translator $translator The translator to inject.
	 * @param Presenter $presenter The presenter in which the language and translator will be used.
	 */
	public function injectTranslator(Translator $translator, Presenter $presenter): void
	{
		$this->translator = $translator;

		// Set language and translator in the presenter template before rendering
		$presenter->onRender[] = function () use ($presenter) {
			$presenter->template->lang = $this->lang;
			$presenter->template->setTranslator($this->getTranslator());
		};
	}


	/**
	 * Gets the Translator instance with the current language set.
	 *
	 * @throws Exception If the translator is not properly set or if the language file is missing.
	 * @return Translator The configured Translator instance.
	 */
	public function getTranslator(): Translator
	{
		// Set the translation file based on the current language
		$this->translator->setTranslate($this->lang);
		return $this->translator;
	}
}
