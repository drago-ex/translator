<?php

declare(strict_types = 1);

use Drago\Localization\DI\TranslatorExtension;
use Drago\Localization\Translator;
use Nette\Application\IPresenterFactory;
use Nette\Application\UI\Presenter;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;

$container = require __DIR__ . '/../../bootstrap.php';


class TestTranslatorExtension extends TestContainer
{
	private function createContainer(): Container
	{
		$params = $this->container->getParameters();
		$loader = new ContainerLoader($params['tempDir'], true);

		$class = $loader->load(function (Compiler $compiler) use ($params): void {
			$compiler->addExtension('translator', new TranslatorExtension(
				$params['appDir'] . '/locale'
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


	private function getPresenterByType(): IPresenterFactory
	{
		return $this->container->getByType(IPresenterFactory::class);
	}


	public function test01(): void
	{
		Assert::type(Translator::class, $this->getTranslatorByType());
	}


	public function test02(): void
	{
		$class = $this->getTranslatorByType();
		Assert::same('Hello, world!', $class->translate('hello.world'));
	}


	public function test03(): void
	{
		/** @var Presenter $presenter */
		$presenter = $this->getPresenterByType()
			->createPresenter('Test');

		$class = new TestTranslatorAdapter;
		$class->lang = 'en';
		$class->injectTranslator($this->getTranslatorByType(), $presenter);

		Assert::type($class->getTranslator(), $this->getTranslatorByType());
	}


	public function test04(): void
	{
		$control = new TestTranslatorControl;
		$control->setTranslator($this->getTranslatorByType());

		Assert::type($control->getTranslator(), $this->getTranslatorByType());
	}


	private function translator(): Translator
	{
		/** @var Presenter $presenter */
		$presenter = $this->getPresenterByType()
			->createPresenter('Test');

		$class = new TestTranslatorAdapter;
		$class->lang = 'en';
		$class->injectTranslator($this->getTranslatorByType(), $presenter);
		return $class->getTranslator();
	}


	public function test05(): void
	{
		$messages = $this->translator()
			->setCustomTranslate(__DIR__ . '/../../locale/en.ini');

		Assert::type('array', $messages);
	}
}

$extension = new TestTranslatorExtension($container);
$extension->run();
