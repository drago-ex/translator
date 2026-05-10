<?php

/**
 * Test: Drago\Localization\TranslatorFinder
 */

declare(strict_types=1);

use Drago\Localization\TranslatorFinder;
use Tester\Assert;
use Tester\TestCase;
use Tracy\Debugger;

require __DIR__ . '/../bootstrap.php';


class TranslatorFinderTest extends TestCase
{
	private string $appDir;
	private string $tempDir;


	public function setUp(): void
	{
		$this->appDir = TempDir . '/finder-app';
		$this->tempDir = TempDir . '/finder-tmp';
		@mkdir($this->appDir, 0777, true);
		@mkdir($this->tempDir, 0777, true);
		@mkdir($this->tempDir . '/cache', 0777, true);
		@mkdir($this->appDir . '/ModuleA/locale', 0777, true);
		@mkdir($this->appDir . '/ModuleB/locale', 0777, true);

		file_put_contents($this->appDir . '/ModuleA/locale/en.neon', "hello: 'Hello'\n");
		file_put_contents($this->appDir . '/ModuleB/locale/cs.neon', "hello: 'Ahoj'\n");
	}


	public function tearDown(): void
	{
		Debugger::$productionMode = false;
	}


	public function testFindsFilesByLanguageInProductionCache(): void
	{
		Debugger::$productionMode = true;
		$finder = new TranslatorFinder($this->appDir, $this->tempDir);

		$enFiles = $finder->findFiles('en');
		$csFiles = $finder->findFiles('cs');

		Assert::count(1, $enFiles);
		Assert::count(1, $csFiles);
		Assert::contains('/en.neon', str_replace('\\', '/', $enFiles[0]));
		Assert::contains('/cs.neon', str_replace('\\', '/', $csFiles[0]));
	}
}

(new TranslatorFinderTest())->run();
