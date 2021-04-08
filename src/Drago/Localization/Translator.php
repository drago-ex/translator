<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Localization;

use Nette;


/**
 * Simple translator.
 */
class Translator implements Nette\Localization\Translator
{
	use Nette\SmartObject;

	private array $message;


	public function __construct(
		public string $translateDir
	) {
	}


	private function parseFile(string $file): array|false
	{
		return parse_ini_file($file);
	}


	public function setTranslate(string $translate): array
	{
		$file = $this->translateDir . '/' . $translate . '.ini';
		$this->message = $this->parseFile($file);
		return $this->message;
	}


	public function setCustomTranslate(string $path): array
	{
		$this->message = $this->parseFile($path);
		return $this->message;
	}


	/**
	 * Translates the given string.
	 */
	public function translate(mixed $message, mixed ...$parameters): string
	{
		return $this->message[$message] ?? $message;
	}
}
