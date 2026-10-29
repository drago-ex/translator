<?php declare(strict_types=1);

/**
 * Test: Drago\Localization\TranslatorFinder
 */

use Drago\Localization\TranslatorFinder;
use Nette\Caching\Storages\MemoryStorage;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';


class TranslatorFinderTest extends TestCase
{
	private string $appDir;


	public function setUp(): void
	{
		$this->appDir = TempDir . '/finder-app';
		@mkdir($this->appDir, 0o777, true); // @ directory may already exist
		@mkdir($this->appDir . '/ModuleA/locale', 0o777, true); // @ directory may already exist
		@mkdir($this->appDir . '/ModuleB/locale', 0o777, true); // @ directory may already exist
		@mkdir($this->appDir . '/ModuleC/locale', 0o777, true); // @ directory may already exist

		file_put_contents($this->appDir . '/ModuleA/locale/en.neon', "hello: 'Hello'\n");
		file_put_contents($this->appDir . '/ModuleB/locale/cs.neon', "hello: 'Ahoj'\n");
		file_put_contents($this->appDir . '/ModuleC/locale/en.neon', "bye: 'Bye'\n");
	}


	public function testFindsFilesByLanguageInProductionCache(): void
	{
		$finder = new TranslatorFinder([], new MemoryStorage);

		$directories = [
			$this->appDir . '/ModuleA/locale',
			$this->appDir . '/ModuleB/locale',
			$this->appDir . '/ModuleC/locale',
		];
		$enFiles = $finder->findFiles('en', $directories);
		$csFiles = $finder->findFiles('cs', $directories);

		Assert::count(2, $enFiles);
		Assert::count(1, $csFiles);
		Assert::contains('/en.neon', str_replace('\\', '/', $enFiles[0]));
		Assert::contains('/cs.neon', str_replace('\\', '/', $csFiles[0]));
	}


	public function testMergesConfiguredDirectories(): void
	{
		$finder = new TranslatorFinder([], new MemoryStorage);

		$enFiles = array_map(
			static fn(string $file): string => str_replace('\\', '/', $file),
			$finder->findFiles('en', [$this->appDir . '/ModuleA/locale', $this->appDir . '/ModuleC/locale']),
		);

		Assert::count(2, $enFiles);
		Assert::contains('/ModuleA/locale/en.neon', $enFiles[0]);
		Assert::contains('/ModuleC/locale/en.neon', $enFiles[1]);
	}


	public function testLoadsBaseFileFirstThenRegionalFilesByName(): void
	{
		$dir = $this->appDir . '/Regional';
		@mkdir($dir, 0o777, true); // @ directory may already exist
		foreach (['cs.neon', 'cs-CZ.neon', 'cs-AT.neon', 'csx.neon', 'en.neon'] as $name) {
			file_put_contents($dir . '/' . $name, "hello: 'x'\n");
		}

		$files = array_map('basename', (new TranslatorFinder)->findFiles('cs', [$dir]));
		Assert::same(['cs.neon', 'cs-AT.neon', 'cs-CZ.neon'], $files);
	}


	public function testInvalidLanguageFindsNothing(): void
	{
		$finder = new TranslatorFinder([], new MemoryStorage);
		$dir = $this->appDir . '/ModuleA/locale';

		Assert::same([], $finder->findFiles('*', [$dir]));
		Assert::same([], $finder->findFiles('../en', [$dir]));
		Assert::same([], $finder->findFiles('', [$dir]));
	}
}

(new TranslatorFinderTest)->run();
