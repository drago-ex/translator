<?php

/**
 * Test: Drago\Localization\ComposerTranslationFinder
 */

declare(strict_types=1);

use Drago\Localization\ComposerPackageProvider;
use Drago\Localization\ComposerTranslationFinder;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';


class ComposerTranslationPackageProvider implements ComposerPackageProvider
{
	public function __construct(
		private readonly string $rootPath,
		private readonly string $packagePath,
	) {
	}


	public function getData(): array
	{
		return [
			'root' => [
				'name' => 'test/root',
				'install_path' => $this->rootPath,
			],
			'versions' => [
				'test/package' => [
					'install_path' => $this->packagePath,
				],
			],
		];
	}
}


class ComposerTranslationFinderTest extends TestCase
{
	private string $tempDir;


	public function setUp(): void
	{
		$this->tempDir = TempDir . '/composer-finder';
		@mkdir($this->tempDir . '/root/app/Translate', 0o777, true);
		@mkdir($this->tempDir . '/vendor/package/src/Translate', 0o777, true);

		file_put_contents($this->tempDir . '/root/composer.json', json_encode([
			'extra' => [
				'drago-translator' => [
					'translation' => 'app/Translate',
				],
			],
		], JSON_THROW_ON_ERROR));

		file_put_contents($this->tempDir . '/vendor/package/composer.json', json_encode([
			'extra' => [
				'drago-translator' => [
					'translation' => 'src/Translate',
				],
			],
		], JSON_THROW_ON_ERROR));
	}


	public function testFindsPackageAndRootDirectoriesInPrecedenceOrder(): void
	{
		$root = realpath($this->tempDir . '/root');
		$package = realpath($this->tempDir . '/vendor/package');
		Assert::type('string', $root);
		Assert::type('string', $package);

		$finder = new ComposerTranslationFinder(new ComposerTranslationPackageProvider($root, $package));
		Assert::same([
			$package . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Translate',
			$root . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Translate',
		], $finder->findDirectories());
	}


	public function testIgnoresUnsafeTranslationPath(): void
	{
		$root = realpath($this->tempDir . '/root');
		$package = realpath($this->tempDir . '/vendor/package');
		Assert::type('string', $root);
		Assert::type('string', $package);

		file_put_contents($package . '/composer.json', json_encode([
			'extra' => [
				'drago-translator' => [
					'translation' => '../Translate',
				],
			],
		], JSON_THROW_ON_ERROR));

		$finder = new ComposerTranslationFinder(new ComposerTranslationPackageProvider($root, $package));
		Assert::same([
			$root . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Translate',
		], $finder->findDirectories());
	}
}

(new ComposerTranslationFinderTest)->run();
