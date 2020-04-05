<?php

declare(strict_types = 1);

use Drago\Localization\Translator;
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


	private function getTranslatorByType(): Translator
	{
		return $this->createContainer()
			->getByType(Translator::class);
	}


	public function test01(): void
	{
		Assert::type(Translator::class, $this->getTranslatorByType());
	}


	public function test02(): void
	{
		$class = $this->getTranslatorByType();
		$class->setTranslate('en');

		Assert::same('Hello, world!', $class->translate('hello.world'));
	}


	public function test03(): void
	{
		$presenter = new Presenter;
		$presenter->lang = 'en';
		$presenter->injectTranslator($this->getTranslatorByType(), $presenter);

		Assert::type($presenter->getTranslator(), $this->getTranslatorByType());
	}


	public function test04(): void
	{
		$class = $this->getTranslatorByType();
		$control = new Control;
		$control->setTranslator($class);

		Assert::type($control->getTranslator(), $class);
	}
}

$extension = new TranslatorExtension($container);
$extension->run();
