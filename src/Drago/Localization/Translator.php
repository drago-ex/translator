<?php

declare(strict_types=1);

namespace Drago\Localization;

use Nette\Localization\Translator as ITranslator;
use Nette\Neon\Exception;
use Nette\Neon\Neon;
use Throwable;
use ValueError;
use function vsprintf;


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
		foreach ($options->translateDirs as $dir) {
			$this->addTranslateDir($dir);
		}
	}


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
	 * Composer-declared package translations are loaded first, followed by the
	 * root project's Composer translation directory and explicit NEON directories.
	 * Later sources override earlier translations.
	 *
	 * @return array<string, string>
	 * @throws Exception
	 * @throws Throwable
	 */
	public function setTranslate(string $lang): array
	{
		$this->messages = [];
		$translateFiles = $this->translatorFinder->findFiles($lang, $this->translateDirs);

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


	public function translate(mixed $message, mixed ...$parameters): string
	{
		$key = is_scalar($message) || $message instanceof \Stringable ? (string) $message : '';
		$translation = $this->messages[$key] ?? $key;
		if ($parameters === [] || !str_contains($translation, '%')) {
			return $translation;
		}

		try {
			return vsprintf($translation, $parameters);
		} catch (ValueError) {
			return $translation;
		}
	}
}
