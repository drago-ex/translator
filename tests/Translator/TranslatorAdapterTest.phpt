<?php declare(strict_types=1);

/**
 * Test: Drago\Localization\TranslatorAdapter
 */

use Drago\Localization\Options;
use Drago\Localization\Translator;
use Drago\Localization\TranslatorFinder;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function createTranslator(): Translator
{
	$dir = TempDir . '/adapter-locale';
	@mkdir($dir, 0o777, true); // @ directory may already exist
	file_put_contents($dir . '/cs.neon', "Hello: Ahoj\n");

	$options = new Options;
	$options->translateDirs = [$dir];
	return new Translator($options, new TranslatorFinder);
}


test('The translator is initialized for the language of the request', function () {
	$adapter = new TestTranslatorAdapter;
	$adapter->lang = 'cs';
	$adapter->translator = createTranslator();

	Assert::same('Ahoj', $adapter->getTranslator()->translate('Hello'));
	Assert::same('cs', $adapter->getTranslator()->getLang());
});


test('The language is loaded on startup, so forms validated in signals get translated messages', function () {
	$translator = createTranslator();
	$presenter = new App\Presentation\Test\TestPresenter;

	$adapter = new TestTranslatorAdapter;
	$adapter->lang = 'cs';
	$adapter->injectTranslator($translator, $presenter);

	Assert::same('Hello', $translator->translate('Hello'));
	Nette\Utils\Arrays::invoke($presenter->onStartup, $presenter);
	Assert::same('Ahoj', $translator->translate('Hello'));
});


test('Without a language from the route, messages are returned untranslated instead of failing', function () {
	$adapter = new TestTranslatorAdapter;
	$adapter->translator = createTranslator();

	Assert::same('Hello', $adapter->getTranslator()->translate('Hello'));
});
