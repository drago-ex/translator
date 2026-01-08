<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Localization;

use Nette\Localization\Translator as ITranslator;
use Nette\Neon\Exception;
use Nette\Neon\Neon;
use Throwable;


/**
 * NEON-based translator implementation.
 *
 * Supports multiple translation sources:
 * - Single translation directory (manual)
 * - Automatic finder scanning the whole app directory
 *
 * Later directories override earlier ones.
 */
class Translator implements ITranslator
{
	/** @var array<string, string> */
	private array $messages = [];

	/** @var string[] */
	private array $translateDirs = [];


	/**
	 * @param Options $options Translator configuration
	 * @param TranslatorFinder $translatorFinder Finder service (used if autoFinder is enabled)
	 */
	public function __construct(
		private readonly Options $options,
		private readonly TranslatorFinder $translatorFinder,
	) {
		if (!$options->autoFinder && $options->translateDir !== null) {
			$this->addTranslateDir($options->translateDir);
		}
	}


	/**
	 * Add a custom translation directory.
	 *
	 * @param string $dir Absolute path to translation directory
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
	 * Loads translations for the given language.
	 *
	 * @param string $lang Language code (e.g. 'cs', 'en')
	 * @return array<string, string>
	 * @throws Exception
	 * @throws Throwable
	 */
	public function setTranslate(string $lang): array
	{
		$this->messages = [];
		$translateFiles = $this->options->autoFinder
			? $this->translatorFinder->findFiles($lang)
			: array_map(fn($dir) => $dir . '/' . $lang . '.neon', $this->translateDirs);

		$this->loadTranslateFiles($translateFiles);
		return $this->messages;
	}


	/**
	 * Helper method to load NEON files and merge messages.
	 *
	 * @param string[] $files
	 * @throws Exception
	 */
	private function loadTranslateFiles(array $files): void
	{
		foreach ($files as $file) {
			if (!is_file($file)) {
				continue;
			}

			$data = Neon::decodeFile($file);
			if (is_array($data)) {
				$this->messages = array_merge($this->messages, $data);
			}
		}
	}


	/**
	 * Translate a message.
	 */
	public function translate(mixed $message, mixed ...$parameters): string
	{
		return $this->messages[$message] ?? (string) $message;
	}
}
