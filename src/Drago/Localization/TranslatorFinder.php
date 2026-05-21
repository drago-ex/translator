<?php

declare(strict_types=1);

namespace Drago\Localization;

use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\Utils\Finder;
use Throwable;
use Tracy\Debugger;


/** Finds all .neon translation files for a given language. */
class TranslatorFinder
{
	public const string Caching = 'translator.search';

	private string $tempDir;


	public function __construct(
		private readonly string $appDir,
		string $tempDir,
	) {
		$this->tempDir = $tempDir . '/cache';
	}


	/**
	 * Returns all .neon files for the given language.
	 * @return list<string>
	 * @throws Throwable
	 */
	public function findFiles(string $lang): array
	{
		$storage = new FileStorage($this->tempDir);
		$cache = new Cache($storage, self::Caching);
		$cacheKey = self::Caching . '.' . $lang;

		/** @var list<string>|null $cacheFiles */
		$cacheFiles = $cache->load($cacheKey);

		if (Debugger::$productionMode === false) {
			$cache->remove($cacheKey);
			return $this->scanFiles($lang);
		}

		if ($cacheFiles !== null) {
			return $cacheFiles;
		}

		$files = $this->scanFiles($lang);
		$cache->save($cacheKey, $files, [
			Cache::All => true,
		]);

		return $files;
	}


	/** @return list<string> */
	private function scanFiles(string $lang): array
	{
		$files = [];
		$finder = Finder::findFiles($lang . '*.neon')
			->from($this->appDir);

		foreach ($finder as $file) {
			$path = $file->getRealPath();
			if (is_string($path)) {
				$files[] = $path;
			}
		}

		return $files;
	}
}
