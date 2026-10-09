<?php

/**
 * Test: Drago\Localization\TranslatorPanel
 */

declare(strict_types=1);

use Drago\Localization\ComposerPackageProvider;
use Drago\Localization\ComposerTranslationFinder;
use Drago\Localization\Options;
use Drago\Localization\Translator;
use Drago\Localization\TranslatorFinder;
use Drago\Localization\TranslatorPanel;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';


class PanelEmptyComposerPackageProvider implements ComposerPackageProvider
{
	public function getData(): array
	{
		return [
			'root' => ['name' => 'test/root', 'install_path' => TempDir],
			'versions' => [],
		];
	}
}


class TranslatorPanelTest extends TestCase
{
	private string $tempDir;


	public function setUp(): void
	{
		$this->tempDir = TempDir . '/translator-panel-test';
		@mkdir($this->tempDir . '/locale', 0o777, true);
	}


	private function createTranslator(): Translator
	{
		$options = new Options;
		$options->translateDirs = [$this->tempDir . '/locale'];

		return new Translator($options, new TranslatorFinder);
	}


	public function testPanelReportsMessagesWithoutTranslation(): void
	{
		file_put_contents($this->tempDir . '/locale/cs.neon', "\"Add to cart\": \"Přidat do košíku\"\n");

		$translator = $this->createTranslator();
		$panel = new TranslatorPanel($translator);
		Assert::contains('not initialized', $panel->getPanel());

		$translator->setTranslate('cs');
		Assert::same('Přidat do košíku', $translator->translate('Add to cart'));
		Assert::same('Checkout <now>', $translator->translate('Checkout <now>'));

		Assert::same(['Checkout <now>'], $translator->getMissing());
		Assert::same(1, $translator->getMessageCount());
		Assert::contains('Translator: cs (1 missing)', $panel->getTab());

		$html = $panel->getPanel();
		Assert::contains('Checkout &lt;now&gt;', $html);
		Assert::contains('cs.neon', $html);
		Assert::contains('locale', $html);
	}


	public function testLanguageWithoutFilesReportsNothingMissing(): void
	{
		$translator = $this->createTranslator();
		$translator->setTranslate('de');
		$translator->translate('Hello');

		$panel = new TranslatorPanel($translator);

		Assert::same([], $translator->getMissing());
		Assert::same('<span title="Drago Translator"><span class="tracy-label">Translator: de</span></span>', $panel->getTab());
	}
}

(new TranslatorPanelTest)->run();
