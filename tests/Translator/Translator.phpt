<?php

/**
 * Test: Drago\Localization\Translator
 */

declare(strict_types=1);

use Drago\Localization\ComposerPackageProvider;
use Drago\Localization\ComposerTranslationFinder;
use Drago\Localization\Options;
use Drago\Localization\Translator;
use Drago\Localization\TranslatorFinder;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';


class EmptyTranslatorComposerPackageProvider implements ComposerPackageProvider
{
	public function getData(): array
	{
		return [
			'root' => ['name' => 'test/root', 'install_path' => TempDir],
			'versions' => [],
		];
	}
}


class TranslatorTest extends TestCase
{
	private string $tempDir;


	public function setUp(): void
	{
		$this->tempDir = TempDir . '/translator-test';
		@mkdir($this->tempDir, 0o777, true);
	}


	public function testComposerPackageTranslationsAreOverriddenByRootTranslations(): void
	{
		$package = $this->tempDir . '/vendor-package';
		$root = $this->tempDir . '/root-package';
		@mkdir($package . '/src/Translate', 0o777, true);
		@mkdir($root . '/app/Translate', 0o777, true);

		file_put_contents($package . '/composer.json', json_encode([
			'extra' => ['drago-translator' => ['translation' => 'src/Translate']],
		], JSON_THROW_ON_ERROR));
		file_put_contents($root . '/composer.json', json_encode([
			'extra' => ['drago-translator' => ['translation' => 'app/Translate']],
		], JSON_THROW_ON_ERROR));
		file_put_contents($package . '/src/Translate/en.neon', "hello: 'Package'\nonlyPackage: 'Package'\n");
		file_put_contents($root . '/app/Translate/en.neon', "hello: 'Application'\nonlyApplication: 'Application'\n");

		$provider = new class ($root, $package) implements ComposerPackageProvider {
			public function __construct(
				private readonly string $root,
				private readonly string $package,
			) {
			}


			public function getData(): array
			{
				return [
					'root' => ['name' => 'test/root', 'install_path' => $this->root],
					'versions' => ['test/package' => ['install_path' => $this->package]],
				];
			}
		};

		$options = new Options;
		$finder = new TranslatorFinder(new ComposerTranslationFinder($provider)->findDirectories());
		$translator = new Translator($options, $finder);
		$translator->setTranslate('en');

		Assert::same('Application', $translator->translate('hello'));
		Assert::same('Package', $translator->translate('onlyPackage'));
		Assert::same('Application', $translator->translate('onlyApplication'));
	}


	public function testManualDirectoriesMergeWithOverrideOrder(): void
	{
		$base = $this->tempDir . '/base';
		$module = $this->tempDir . '/module';
		@mkdir($base, 0o777, true);
		@mkdir($module, 0o777, true);

		file_put_contents($base . '/en.neon', "hello: 'Hello'\nkey: 'base'\n");
		file_put_contents($module . '/en.neon', "key: 'module'\n");

		$options = new Options;
		$options->translateDirs = [$base, $module];

		$finder = new TranslatorFinder;
		$translator = new Translator($options, $finder);
		$translator->setTranslate('en');

		Assert::same('Hello', $translator->translate('hello'));
		Assert::same('module', $translator->translate('key'));
		Assert::same('missing', $translator->translate('missing'));
	}


	public function testParametersAreFormattedAndMismatchedPlaceholdersDoNotThrow(): void
	{
		$dir = $this->tempDir . '/params';
		@mkdir($dir, 0o777, true);
		file_put_contents($dir . '/en.neon', "\"Hello, %s!\": \"Hi, %s!\"\n");

		$options = new Options;
		$options->translateDirs = [$dir];

		$translator = new Translator($options, new TranslatorFinder);
		$translator->setTranslate('en');

		Assert::same('Hi, Jane!', $translator->translate('Hello, %s!', 'Jane'));
		Assert::same('You have 3 items', $translator->translate('You have %d items', 3));
		Assert::same('%s and %s', $translator->translate('%s and %s', 'one'));
		Assert::same('Plain', $translator->translate('Plain', 'unused'));
	}
}

(new TranslatorTest)->run();
