<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Localization;

use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\Utils\Finder;
use Throwable;
use Tracy\Debugger;


/**
 * Finds all .neon translation files for a given language under the app directory.
 * Caches the list of files for production mode to avoid repeated scanning.
 */
class TranslatorFinder
{
	public const string Caching = 'translator.search';

	private string $appDir;
	private string $tempDir;


	/**
	 * @param string $appDir Base application directory (%appDir%)
	 * @param string $tempDir Optional temp directory (%tempDir%)
	 */
	public function __construct(string $appDir, string $tempDir)
	{
		$this->appDir = $appDir;
		$this->tempDir = $tempDir . '/cache';
	}


	/**
	 * Returns all .neon files for the given language.
	 *
	 * In development mode, cache is ignored and files are always scanned.
	 * In production mode, the result is cached to improve performance.
	 *
	 * @param string $lang Language code (cs, en, etc.)
	 * @return string[] Absolute paths to the neon files
	 * @throws Throwable
	 */


	public function findFiles(string $lang): array
	{
		$storage = new FileStorage($this->tempDir);
		$cache = new Cache($storage, self::Caching);
		$cacheFiles = $cache->load(self::Caching);

		if (Debugger::$productionMode === false) {
			$files = $this->scanFiles($lang);
			$cache->remove(self::Caching);

		} else {
			if (!$cacheFiles) {
				$files = $this->scanFiles($lang);
				$cache->save(self::Caching, $files, [
					Cache::All => true,
				]);
			}
		}

		return $cacheFiles ?: $files;
	}


	/**
	 * Recursively scan appDir for translate neon files.
	 *
	 * @param string $lang
	 * @return string[]
	 */
	private function scanFiles(string $lang): array
	{
		$files = [];
		$finder = Finder::findFiles($lang . '*.neon')
			->from($this->appDir);

		foreach ($finder as $file) {
			$files[] = $file->getRealPath();
		}

		return $files;
	}
}
