<?php

declare(strict_types=1);

namespace Drago\Localization;

use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Presenter;
use Nette\Neon\Exception;
use Throwable;


/** Presenter helper for translator integration. */
trait TranslatorAdapter
{
	#[Persistent]
	public string $lang;

	public Translator $translator;
	private bool $translatorInitialized = false;


	/** Inject translator service. */
	public function injectTranslator(Translator $translator, Presenter $presenter): void
	{
		$this->translator = $translator;
		$presenter->onRender[] = function () use ($presenter): void {
			$template = $presenter->getTemplate();
			if ($template instanceof TranslateTemplate) {
				$template->lang = $this->lang;
				$template->setTranslator($this->getTranslator());
			}
		};
	}


	/**
	 * Returns initialized translator for current language.
	 * @throws Exception
	 * @throws Throwable
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
