<?php

declare(strict_types=1);

namespace Drago\Localization;

use JsonException;
use function is_array;
use function is_file;
use function is_string;
use function json_decode;
use function preg_match;
use function rtrim;
use function str_replace;
use function str_starts_with;
use function trim;


/** Finds translation directories declared in Composer package metadata. */
class ComposerTranslationFinder
{
	private const string ExtraKey = 'drago-translator';
	private const string TranslationKey = 'translation';


	/** @var list<string>|null */
	private ?array $directories = null;


	public function __construct(
		private readonly ComposerPackageProvider $packageProvider,
	) {
	}


	/**
	 * @return list<string>
	 */
	public function findDirectories(): array
	{
		if ($this->directories !== null) {
			return $this->directories;
		}

		$directories = [];
		$data = $this->packageProvider->getData();
		$rootName = $data['root']['name'];
		$rootPath = $data['root']['install_path'];

		foreach ($data['versions'] as $packageName => $package) {
			if ($packageName === $rootName || !isset($package['install_path'])) {
				continue;
			}

			$path = $package['install_path'];
			$translation = $this->readComposerTranslation($path);
			if ($translation !== null && !$this->addTranslationDirectory($directories, $path, $translation)) {
				$this->addTranslationDirectory($directories, $rootPath, $translation);
			}
		}

		$this->addTranslationDirectory($directories, $rootPath, $this->readComposerTranslation($rootPath));

		return $this->directories = $directories;
	}


	/**
	 * @param list<string> $directories
	 */
	private function addTranslationDirectory(array &$directories, string $basePath, ?string $relativePath): bool
	{
		if ($relativePath === null || !$this->isRelativePath($relativePath)) {
			return false;
		}

		$path = rtrim($basePath, '/\\') . DIRECTORY_SEPARATOR
			. str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);

		if (!is_dir($path)) {
			return false;
		}

		if (!in_array($path, $directories, true)) {
			$directories[] = $path;
		}

		return true;
	}


	private function readComposerTranslation(string $packagePath): ?string
	{
		$file = rtrim($packagePath, '/\\') . DIRECTORY_SEPARATOR . 'composer.json';
		if (!is_file($file)) {
			return null;
		}

		try {
			$json = file_get_contents($file);
			if (!is_string($json)) {
				return null;
			}

			$data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
		} catch (JsonException) {
			return null;
		}

		if (!is_array($data)) {
			return null;
		}

		$composerExtra = $data['extra'] ?? null;
		if (!is_array($composerExtra)) {
			return null;
		}

		$extra = $composerExtra[self::ExtraKey] ?? null;
		if (!is_array($extra)) {
			return null;
		}

		$translation = $extra[self::TranslationKey] ?? null;
		return is_string($translation) ? trim($translation) : null;
	}


	private function isRelativePath(string $path): bool
	{
		$path = trim(str_replace('\\', '/', $path));
		if ($path === '' || str_starts_with($path, '/') || preg_match('/^[A-Za-z]:\//', $path) === 1) {
			return false;
		}

		return !in_array('..', explode('/', $path), true);
	}
}
