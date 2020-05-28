<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Localization;

use Nette;


/**
 * Simple translator.
 */
class Translator implements Nette\Localization\ITranslator
{
	use Nette\SmartObject;

	/** @var array */
	private $message;

	/** @var string */
	private $translateDir;


	public function __construct(string $translateDir)
	{
		$this->translateDir = $translateDir;
	}


	/**
	 * @return array|false
	 */
	private function parseFile(string $file)
	{
		$parse = parse_ini_file($file);
		return $parse;
	}


	public function setTranslate(string $translate): array
	{
		$file = $this->translateDir . '/' . $translate . '.ini';
		$this->message = $this->parseFile($file);
		return $this->message;
	}


	public function setCustomTranslate(string $path): array
	{
		$this->message = parse_ini_file($path);
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
