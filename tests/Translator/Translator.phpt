<?php

/**
 * Test: Drago\Localization\Translator
 */

declare(strict_types=1);

use Drago\Localization\Options;
use Drago\Localization\Translator;
use Drago\Localization\TranslatorFinder;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';


class TranslatorTest extends TestCase
{
	private string $tempDir;


	public function setUp(): void
	{
		$this->tempDir = TempDir . '/translator-test';
		@mkdir($this->tempDir, 0o777, true);
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
		$options->autoFinder = false;
		$options->translateDirs = [$base, $module];

		$translator = new Translator($options, new TranslatorFinder($this->tempDir, $this->tempDir));
		$translator->setTranslate('en');

		Assert::same('Hello', $translator->translate('hello'));
		Assert::same('module', $translator->translate('key'));
		Assert::same('missing', $translator->translate('missing'));
	}
}

(new TranslatorTest)->run();
