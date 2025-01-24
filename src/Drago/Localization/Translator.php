<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Localization;

use Nette;
use Nette\Neon\Exception;
use Nette\Neon\Neon;


/**
 * Translator class provides simple localization by loading translations from NEON files.
 * It allows setting default and custom translation files and translates messages.
 */
class Translator implements Nette\Localization\Translator
{
	/** @var array<string, string> Array of translation messages. */
	private array $messages = [];

	/** @var string Directory where translation files are stored. */
	private string $translateDir;


	/**
	 *
	 *
	 * @param string $translateDir The directory containing translation files.
	 */
	public function __construct(string $translateDir)
	{
		$this->translateDir = $translateDir;
	}


	/**
	 * Decodes a NEON translation file into an associative array.
	 *
	 * @param string $file Path to the translation file.
	 *
	 * @return array<string, string> Decoded translation messages.
	 *
	 * @throws Exception If the file is not found or if decoding fails.
	 */
	private function decodeFile(string $file): array
	{
		if (!is_file($file)) {
			throw new Exception("File '$file' not found.");
		}

		return Neon::decodeFile($file);
	}


	/**
	 * Loads translations from the default translation file located in the translate directory.
	 *
	 * @param string $translate Name of the translation file (without extension).
	 *
	 * @return array<string, string> Loaded translation messages.
	 *
	 * @throws Exception If the translation file cannot be loaded or decoded.
	 */
	public function setTranslate(string $translate): array
	{
		$file = $this->translateDir . '/' . $translate . '.neon';
		return $this->messages = $this->decodeFile($file);
	}


	/**
	 * Loads translations from a custom translation file located at a specific path.
	 *
	 * @param string $path Path to the directory containing the translation file.
	 * @param string $translate Name of the translation file (without extension).
	 *
	 * @return array<string, string> Loaded translation messages.
	 *
	 * @throws Exception If the translation file cannot be loaded or decoded.
	 */
	public function setCustomTranslate(string $path, string $translate): array
	{
		$file = $path . $translate . '.neon';
		return $this->messages = $this->decodeFile($file);
	}


	/**
	 * Translates the given message by looking it up in the loaded translations.
	 *
	 * If the message is not found, it will return the original message as a string.
	 *
	 * @param mixed $message The message to translate.
	 * @param mixed ...$parameters Parameters for string interpolation (not used in this implementation).
	 *
	 * @return string Translated message or original message if not found.
	 */
	public function translate(mixed $message, mixed ...$parameters): string
	{
		return $this->messages[$message] ?? (string) $message;
	}
}
