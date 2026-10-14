<?php

declare(strict_types=1);

namespace Drago\Localization;

use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\Utils\Finder;
use Throwable;


/** Finds translation files from Composer packages and explicitly configured directories. */
class TranslatorFinder
{
	public const string Caching = 'translator.search';

	/** Accepted language codes, e.g. cs, en, cs-CZ. Anything else finds no files. */
	private const string LangPattern = '~^[a-z]{2,3}([_-][A-Za-z0-9]+)?$~';

	private ?Cache $cache;


	/**
	 * @param list<string> $composerDirectories Directories discovered from Composer metadata.
	 * @param Storage|null $storage Optional cache for found files; skipped in debug mode.
	 */
	public function __construct(
		private readonly array $composerDirectories = [],
		?Storage $storage = null,
		private readonly bool $debugMode = false,
	) {
		$this->cache = $storage === null ? null : new Cache($storage, self::Caching);
	}


	/**
	 * Returns Composer-discovered directories followed by the explicit ones.
	 * @param list<string> $translateDirs
	 * @return list<string>
	 */
	public function findDirectories(array $translateDirs = []): array
	{
		return array_values(array_unique([...$this->composerDirectories, ...$translateDirs]));
	}


	/**
	 * Returns all .neon files for the given language: `<lang>.neon` first, then `<lang>-*.neon` by name.
	 * @param list<string> $translateDirs
	 * @return list<string>
	 * @throws Throwable
	 */
	public function findFiles(string $lang, array $translateDirs = []): array
	{
		if (preg_match(self::LangPattern, $lang) !== 1) {
			return [];
		}

		$directories = $this->findDirectories($translateDirs);
		if ($this->cache === null || $this->debugMode) {
			return $this->scanDirectories($lang, $directories);
		}

		/** @var list<string> $files */
		$files = $this->cache->load(
			$lang . '.' . md5(implode('|', $directories)),
			fn(): array => $this->scanDirectories($lang, $directories),
		);
		return $files;
	}


	/**
	 * @param list<string> $directories
	 * @return list<string>
	 */
	private function scanDirectories(string $lang, array $directories): array
	{
		$files = [];
		foreach ($directories as $directory) {
			if (!is_dir($directory)) {
				continue;
			}

			$exact = realpath($directory . DIRECTORY_SEPARATOR . $lang . '.neon');
			if ($exact !== false) {
				$files[] = $exact;
			}

			$finder = Finder::findFiles($lang . '-*.neon')->in($directory)->sortByName();
			foreach ($finder as $file) {
				$path = $file->getRealPath();
				if (is_string($path)) {
					$files[] = $path;
				}
			}
		}

		return array_values(array_unique($files));
	}
}
