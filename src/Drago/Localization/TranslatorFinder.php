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
	 * @param list<string> $exclude
	 * @return list<string>
	 * @throws Throwable
	 */
	public function findFiles(string $lang, array $exclude = []): array
	{
		$storage = new FileStorage($this->tempDir);
		$cache = new Cache($storage, self::Caching);
		$exclude = $this->normalizeExclude($exclude);
		$cacheKey = self::Caching . '.' . $lang . '.' . md5(implode('|', $exclude));

		/** @var list<string>|null $cacheFiles */
		$cacheFiles = $cache->load($cacheKey);

		if (Debugger::$productionMode === false) {
			$cache->remove($cacheKey);
			return $this->scanFiles($lang, $exclude);
		}

		if ($cacheFiles !== null) {
			return $cacheFiles;
		}

		$files = $this->scanFiles($lang, $exclude);
		$cache->save($cacheKey, $files, [
			Cache::All => true,
		]);

		return $files;
	}


	/**
	 * @param list<string> $exclude
	 * @return list<string>
	 */
	private function scanFiles(string $lang, array $exclude): array
	{
		$files = [];
		$finder = Finder::findFiles($lang . '*.neon')
			->from($this->appDir);

		if ($exclude !== []) {
			$finder->exclude($exclude);
		}

		foreach ($finder as $file) {
			$path = $file->getRealPath();
			if (is_string($path)) {
				$files[] = $path;
			}
		}

		return $files;
	}


	/**
	 * @param list<string> $exclude
	 * @return list<string>
	 */
	private function normalizeExclude(array $exclude): array
	{
		$appDir = rtrim(str_replace('\\', '/', $this->appDir), '/');
		$masks = [];

		foreach ($exclude as $dir) {
			$dir = rtrim(str_replace('\\', '/', $dir), '/');
			if ($dir === '') {
				continue;
			}

			if (str_starts_with($dir, $appDir . '/')) {
				$dir = substr($dir, strlen($appDir) + 1);
			}

			if ($dir !== '') {
				$masks[] = $dir;
			}
		}

		return array_values(array_unique($masks));
	}
}
