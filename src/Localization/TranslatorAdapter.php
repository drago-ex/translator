<?php

declare(strict_types=1);

namespace Localization;

use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Neon\Exception;
use Throwable;


trait TranslatorAdapter
{
	#[Persistent]
	public string $lang;

	public Translator $translator;
	private bool $translatorInitialized = false;


	public function injectTranslator(Translator $translator, Presenter $presenter): void
	{
		$this->translator = $translator;
		$presenter->onRender[] = function () use ($presenter): void {
			$template = $presenter->getTemplate();
			if ($template instanceof Template) {
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
