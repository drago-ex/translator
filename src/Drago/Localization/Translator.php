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
 * Handles loading and translating messages from NEON files.
 * Supports multiple translation directories, allowing global and module-specific translations.
 */
class Translator implements Nette\Localization\Translator
{
	/** @var array<string, string> Loaded and merged translation messages */
	private array $messages = [];

	/** @var string[] Registered translation directories */
	private array $translateDirs = [];


	/**
	 * @param string $translateDir Path to the main translation directory
	 * @param Options $options Optional module-specific translation directory
	 */
	public function __construct(string $translateDir, Options $options)
	{
		$this->addTranslateDir($translateDir);
		if ($options->moduleDir) {
			$this->addTranslateDir($options->moduleDir);
		}
	}


	/**
	 * Adds a translation directory.
	 * Later directories override earlier ones if keys collide.
	 *
	 * @param string $dir Path to translation directory
	 */
	public function addTranslateDir(string $dir): void
	{
		if (!is_dir($dir)) {
			return;
		}

		if (!in_array($dir, $this->translateDirs, true)) {
			$this->translateDirs[] = $dir;
		}
	}


	/**
	 * Loads and merges translations for the given language from all registered directories.
	 *
	 * @param string $lang Language code (e.g., 'cs', 'en')
	 * @return array<string, string> Merged translations
	 * @throws Exception
	 */
	public function setTranslate(string $lang): array
	{
		$this->messages = [];
		foreach ($this->translateDirs as $dir) {
			$file = $dir . '/' . $lang . '.neon';

			if (is_file($file)) {
				$data = Neon::decodeFile($file);
				if (is_array($data)) {
					$this->messages = array_merge($this->messages, $data);
				}
			}
		}

		return $this->messages;
	}


	/**
	 * Translates a message.
	 *
	 * @param mixed $message The original message
	 * @param mixed ...$parameters Additional parameters (currently unused)
	 * @return string Translated message or original if not found
	 */
	public function translate(mixed $message, mixed ...$parameters): string
	{
		return $this->messages[$message] ?? (string) $message;
	}
}
