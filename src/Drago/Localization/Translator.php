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
	 * @throws \Exception
	 */
	public function __construct(string $filename)
	{
		$this->message = $this->parse($filename);
	}


	/**
	 * Processing translate file.
	 * @throws \Exception
	 */
	private function parse(string $filename): array
	{
		if (!is_file($filename)) {
			throw new \Exception('The translation file was not found.');
		}
		return parse_ini_file($filename);
	}


	/**
	 * Translates the given string.
	 */
	public function translate($message, ...$parameters): string
	{
		return isset($this->message[$message]) ? $this->message[$message] : $message;
	}
}
