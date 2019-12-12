<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Localization;

use Nette\Localization\ITranslator;
use Nette\SmartObject;


/**
 * Simple translator.
 */
class Translator implements ITranslator
{
	use SmartObject;

	/** @var array */
	private $message;


	/**
	 * Path to the translation file.
	 * @throws \Exception
	 */
	public function setFile(string $file): array
	{
		$file = $file . '.ini';
		if (!is_file($file)) {
			throw new \Exception('The translation file was not found.');
		}

		$this->message = parse_ini_file($file);
		return $this->message;
	}


	/**
	 * Translates the given string.
	 */
	public function translate($message, ...$parameters): string
	{
		return isset($this->message[$message])
			? $this->message[$message]
			: $message;
	}
}
