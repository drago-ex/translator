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
trait Locale
{
	/**
	 * @var string
	 * @persistent
	 */
	public $lang;

	/**
	 * @var Translator
	 * @inject
	 */
	public $translator;


	/**
	 * Create a translation.
	 * @throws \Exception
	 */
	public function createTranslator(string $file): Translator
	{
		$translator = $this->translator;
		$translator->setFile($file);
		return $translator;
	}
}
