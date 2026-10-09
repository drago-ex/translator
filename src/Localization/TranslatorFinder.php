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
	 * Returns all .neon files for the given language.
	 * @param list<string> $translateDirs
	 * @return list<string>
	 * @throws Throwable
	 */
	public function findFiles(string $lang, array $translateDirs = []): array
	{
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

			$finder = Finder::findFiles($lang . '*.neon')->in($directory);
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
