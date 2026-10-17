<?php

declare(strict_types=1);

namespace Drago\Localization;

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
				$template->lang = $this->getLang();
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
			$this->translator->setTranslate($this->getLang());
			$this->translatorInitialized = true;
		}
		return $this->translator;
	}


	/**
	 * Language from the persistent parameter; empty when the route does not provide one,
	 * so messages are then returned untranslated instead of failing.
	 */
	private function getLang(): string
	{
		return isset($this->lang) ? $this->lang : '';
	}
}
