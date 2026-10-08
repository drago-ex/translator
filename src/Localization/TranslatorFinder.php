<?php

declare(strict_types=1);

namespace Localization;

use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\Utils\Finder;
use Throwable;
use Tracy\Debugger;


/** Finds translation files from Composer packages and explicitly configured directories. */
class TranslatorFinder
{
	public const string Caching = 'translator.search';

	private string $tempDir;
	private ComposerTranslationFinder $composerFinder;


	public function __construct(
		string $tempDir,
		?ComposerTranslationFinder $composerFinder = null,
	) {
		$this->tempDir = $tempDir . '/cache';
		if (!is_dir($this->tempDir)) {
			mkdir($this->tempDir, 0o777, true);
		}
		$this->composerFinder = $composerFinder ?? new ComposerTranslationFinder(new InstalledComposerPackageProvider);
	}


	/**
	 * Returns Composer-discovered directories followed by the explicit ones.
	 * @param list<string> $translateDirs
	 * @return list<string>
	 */
	public function findDirectories(array $translateDirs = []): array
	{
		return array_values(array_unique([...$this->composerFinder->findDirectories(), ...$translateDirs]));
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

		$cache = new Cache(new FileStorage($this->tempDir), self::Caching);
		$cacheKey = self::Caching . '.' . $lang . '.' . md5(implode('|', $directories));

		/** @var list<string>|null $cacheFiles */
		$cacheFiles = $cache->load($cacheKey);
		if (Debugger::$productionMode === false) {
			$cache->remove($cacheKey);
			return $this->scanDirectories($lang, $directories);
		}

		if ($cacheFiles !== null) {
			return $cacheFiles;
		}

		$files = $this->scanDirectories($lang, $directories);
		$cache->save($cacheKey, $files);

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
