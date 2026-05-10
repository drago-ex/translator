<?php
// source: mock://1.neon

/** @noinspection PhpParamsInspection,PhpMethodMayBeStaticInspection */

declare(strict_types=1);

class Container_1c03a4c6dd extends Nette\DI\Container
{
	protected array $aliases = [];

	protected array $wiring = [
		'Nette\DI\Container' => [['container']],
		'Drago\Localization\TranslatorFinder' => [['translator.finder']],
		'Nette\Localization\Translator' => [['translator.translator']],
		'Drago\Localization\Translator' => [['translator.translator']],
	];


	/**
	 * @param  mixed[]  $params
	 */
	public function __construct(array $params = [])
	{
		parent::__construct($params);
	}


	public function createServiceContainer(): Nette\DI\Container
	{
		return $this;
	}


	public function createServiceTranslator__finder(): Drago\Localization\TranslatorFinder
	{
		return new Drago\Localization\TranslatorFinder(
			'C:\Users\zdene\PhpstormProjects\translator2\tests',
			'C:\Users\zdene\PhpstormProjects\translator2\tests/tmp',
		);
	}


	public function createServiceTranslator__translator(): Drago\Localization\Translator
	{
		return new Drago\Localization\Translator(
			\Nette\PhpGenerator\Dumper::createObject(\Drago\Localization\Options::class, [
			'autoFinder' => false,
			'translateDirs' => ['locale'],
		]),
			$this->getService('translator.finder'),
		);
	}


	public function initialize(): void
	{
	}


	/**
	 * @return mixed[]
	 */
	protected function getStaticParameters(): array
	{
		return [];
	}
}
