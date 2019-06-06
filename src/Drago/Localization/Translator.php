<?php

declare(strict_types = 1);

/**
 * Drago Localization
 * Package built on Nette Framework
 */
namespace Drago\Localization;

use Nette;
use Exception;


/**
 * Simple translator.
 * @package Drago\Localization
 */
class Translator implements Nette\Localization\ITranslator
{
	use Nette\SmartObject;

	/** @var array */
	private $message;


	public function __construct(string $filename)
	{
		$this->message = $this->parse($filename);
	}


	/**
	 * Processing translate file.
	 * @throws Exception
	 */
	private function parse(string $filename): array
	{
		if (!is_file($filename)) {
			throw new Exception('The translation file was not found.');
		}
		return parse_ini_file($filename);
	}


	/**
	 * Translates the given string.
	 */
	function translate($message, ...$parameters): string
	{
		return isset($this->message[$message]) ? $this->message[$message] : $message;
	}
}
