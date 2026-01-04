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
 * NEON-based translator implementation.
 *
 * Supports multiple translation directories.
 * Later directories override earlier ones.
 */
class Translator implements Nette\Localization\Translator
{
	/** @var array<string, string> */
	private array $messages = [];

	/** @var string[] */
	private array $translateDirs = [];


	/**
	 * @param string  $translateDir Base translation directory
	 * @param Options $options      Translator configuration
	 */
	public function __construct(string $translateDir, Options $options)
	{
		$this->addTranslateDir($translateDir);

		if ($options->moduleLocaleDir) {
			$this->addTranslateDir($options->moduleLocaleDir);
		}
	}


	/**
	 * @param string $dir Translation directory path
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
	 * Loads translations for given language.
	 *
	 * @param string $lang Language code (e.g. 'cs', 'en')
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


	public function translate(mixed $message, mixed ...$parameters): string
	{
		return $this->messages[$message] ?? (string) $message;
	}
}
