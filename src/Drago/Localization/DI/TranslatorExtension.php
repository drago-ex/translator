<?php

declare(strict_types=1);

namespace Drago\Localization\DI;

use Drago\Localization\Options;
use Drago\Localization\Translator;
use Drago\Localization\TranslatorFinder;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;


/** Nette DI extension for registering the translator service. */
class TranslatorExtension extends CompilerExtension
{
	public function __construct(
		private readonly string $appDir,
		private readonly string $tempDir,
	) {
	}


	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'autoFinder' => Expect::bool(true),
			'translateDirs' => Expect::arrayOf(Expect::string())->default([]),
		]);
	}


	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$options = (new Processor)->process(
			Expect::from(new Options),
			$this->config,
		);

		// Register TranslationFinder service.
		$builder->addDefinition($this->prefix('finder'))
			->setFactory(TranslatorFinder::class, [$this->appDir, $this->tempDir]);

		// Register Translator service.
		$builder->addDefinition($this->prefix('translator'))
			->setFactory(Translator::class, [$options, $this->prefix('@finder')]);
	}
}
