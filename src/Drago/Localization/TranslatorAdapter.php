<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Localization;

use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Presenter;


/**
 * Simple translator.
 */
trait TranslatorAdapter
{
	#[Persistent]
	public string $lang;

	public Translator $translator;


	public function injectTranslator(Translator $translator, Presenter $presenter): void
	{
		$this->translator = $translator;
		$presenter->onRender[] = function () use ($presenter) {
			$presenter->template->lang = $this->lang;
			$presenter->template->setTranslator($this->getTranslator());
		};
	}


	public function getTranslator(): Translator
	{
		$this->translator->setTranslate($this->lang);
		return $this->translator;
	}
}
