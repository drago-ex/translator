<?php

declare(strict_types = 1);

use Drago\Localization\Translator;
use Nette\Application\IPresenterFactory;
use Nette\DI;
use Tester\Assert;

$container = require __DIR__ . '/../../bootstrap.php';


class TranslatorExtension extends TestContainer
{
	private function createContainer(): DI\Container
	{
		$params = $this->container->getParameters();
		$loader = new DI\ContainerLoader($params['tempDir'], true);

		$class = $loader->load(function (DI\Compiler $compiler) use ($params): void {
			$compiler->addExtension('translator', new Drago\Localization\DI\TranslatorExtension(
				$params['appDir'] . '/locale'
			));
		});
		return new $class;
	}


	/**
	 * @return Translator|object|null
	 */
	private function getTranslatorByType()
	{
		$translator = $this->createContainer()->getByType(Translator::class);
		$translator->setTranslate('en');
		return $translator;
	}


	/**
	 * @return IPresenterFactory|object|null
	 */
	private function getPresenterByType()
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
		/** @var Nette\Application\UI\Presenter $presenter */
		$presenter = $this->getPresenterByType()->createPresenter('Test');
		$class = new TranslatorAdapter;
		$class->lang = 'en';
		$class->injectTranslator($this->getTranslatorByType(), $presenter);

		Assert::type($class->getTranslator(), $this->getTranslatorByType());
	}


	public function test04(): void
	{
		$class = $this->getTranslatorByType();
		$control = new TranslatorControl;
		$control->setTranslator($class);

		Assert::type($control->getTranslator(), $class);
	}
}

$extension = new TranslatorExtension($container);
$extension->run();
