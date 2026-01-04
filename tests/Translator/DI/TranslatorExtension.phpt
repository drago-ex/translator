<?php

/**
 * Test: Drago\Localization\DI\TranslatorExtension
 */

declare(strict_types=1);

use Drago\Localization\DI\TranslatorExtension;
use Drago\Localization\Translator;
use Nette\Application\IPresenterFactory;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__ . '/../../bootstrap.php';


class TestTranslatorExtension extends TestCase
{
	protected Container $container;


	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	private function createContainer(): Container
	{
		$params = $this->container->getParameters();
		$loader = new ContainerLoader($params['tempDir'], true);

		$class = $loader->load(function (Compiler $compiler) use ($params): void {
			$compiler->addExtension('translator', new TranslatorExtension(
				$params['appDir'] . '/locale',
			));
		});
		return new $class;
	}


	private function getTranslatorByType(): Translator
	{
		$translator = $this->createContainer()->getByType(Translator::class);
		$translator->setTranslate('en');
		return $translator;
	}


	public function test01(): void
	{
		Assert::type(Translator::class, $this->getTranslatorByType());
	}


	public function test02(): void
	{
		$class = $this->getTranslatorByType();
		Assert::same('Hello, world!', $class->translate('Hello, world!'));
	}


	public function test03(): void
	{
		$presenter = $this->container->getByType(IPresenterFactory::class);
		$testPresenter = $presenter->createPresenter('Test');

		$class = new TestTranslatorAdapter;
		$class->lang = 'en';
		$class->injectTranslator($this->getTranslatorByType(), $testPresenter);

		Assert::type($class->getTranslator(), $this->getTranslatorByType());
	}


	private function translator(): Translator
	{
		$presenter = $this->container->getByType(IPresenterFactory::class);
		$testPresenter = $presenter->createPresenter('Test');

		$class = new TestTranslatorAdapter;
		$class->lang = 'en';
		$class->injectTranslator($this->getTranslatorByType(), $testPresenter);
		return $class->getTranslator();
	}
}

(new TestTranslatorExtension($container))->run();
