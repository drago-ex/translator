<?php

declare(strict_types=1);

namespace Drago\Localization\DI;

use Drago\Localization\ComposerTranslationFinder;
use Drago\Localization\InstalledComposerPackageProvider;
use Drago\Localization\Options;
use Drago\Localization\Translator;
use Drago\Localization\TranslatorFinder;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;


class TranslatorExtension extends CompilerExtension
{
	public function __construct(
		private readonly string $tempDir,
	) {
	}


	public function getConfigSchema(): Schema
	{
		return Expect::structure([
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

		$builder->addDefinition($this->prefix('composerProvider'))
			->setFactory(InstalledComposerPackageProvider::class);

		$builder->addDefinition($this->prefix('composerFinder'))
			->setFactory(ComposerTranslationFinder::class, [$this->prefix('@composerProvider')]);

		$builder->addDefinition($this->prefix('finder'))
			->setFactory(TranslatorFinder::class, [$this->tempDir, $this->prefix('@composerFinder')]);

		$builder->addDefinition($this->prefix('translator'))
			->setFactory(Translator::class, [$options, $this->prefix('@finder')]);
	}
}
