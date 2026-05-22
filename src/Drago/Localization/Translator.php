<?php

declare(strict_types=1);

namespace Drago\Localization;

use Nette\Localization\Translator as ITranslator;
use Nette\Neon\Exception;
use Nette\Neon\Neon;
use Throwable;


/** NEON-based translator implementation. */
class Translator implements ITranslator
{
	/** @var array<string, string> */
	private array $messages = [];

	/** @var list<string> */
	private array $translateDirs = [];


	public function __construct(
		private readonly Options $options,
		private readonly TranslatorFinder $translatorFinder,
	) {
		if (!$options->autoFinder) {
			foreach ($options->translateDirs as $dir) {
				$this->addTranslateDir($dir);
			}
		}
	}


	/** Add a custom translation directory. */
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
	 * @return array<string, string>
	 * @throws Exception
	 * @throws Throwable
	 */
	public function setTranslate(string $lang): array
	{
		$this->messages = [];
		$translateFiles = $this->options->autoFinder
			? $this->translatorFinder->findFiles($lang)
			: array_map(fn(string $dir): string => $dir . '/' . $lang . '.neon', $this->translateDirs);

		$this->loadTranslateFiles($translateFiles);
		return $this->messages;
	}


	/**
	 * @param list<string> $files
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

				/** @var array<string, string> $data */
				$this->messages = array_merge($this->messages, $data);
			}
		}
	}


	/** Translate a message. */
	public function translate(mixed $message, mixed ...$parameters): string
	{
		$key = is_scalar($message) || $message instanceof \Stringable ? (string) $message : '';
		return $this->messages[$key] ?? $key;
	}
}
