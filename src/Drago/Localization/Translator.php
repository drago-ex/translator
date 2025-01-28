<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Localization;

use Nette;
use Nette\Neon\Neon;


/**
 * Translator class provides simple localization by loading translations from NEON files.
 * It allows setting default and custom translation files and translates messages.
 */
class Translator implements Nette\Localization\Translator
{
	/** @var array<string, string> Array of translation messages. */
	private array $messages = [];


	public function __construct(
		public string $translateDir,
	) {
	}


	/**
	 * Decodes the contents of a NEON file and returns it as an array.
	 */
	private function decodeFile(string $file): array
	{
		$arr = [];
		if (is_file($file)) {
			$arr = Neon::decodeFile($file);
		}
		return $arr;
	}


	/**
	 * Sets the default translation file and loads its content into messages.
	 */
	public function setTranslate(string $translate): array
	{
		$file = $this->translateDir . '/' . $translate . '.neon';
		return $this->messages = $this->decodeFile($file);
	}


	/**
	 * Sets a custom translation file from a given path and loads its content into messages.
	 */
	public function setCustomTranslate(string $path, string $translate): array
	{
		$file = $path . $translate . '.neon';
		return $this->messages = $this->decodeFile($file);
	}


	/**
	 * Translates a message, returns the translated string or the original message if no translation is found.
	 */
	public function translate(mixed $message, mixed ...$parameters): string
	{
		return $this->messages[$message] ?? (string) $message;
	}
}
