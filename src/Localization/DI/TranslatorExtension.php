<?php

declare(strict_types=1);

namespace Localization\DI;

use Localization\ComposerTranslationFinder;
use Localization\InstalledComposerPackageProvider;
use Localization\Options;
use Localization\Translator;
use Localization\TranslatorFinder;
use Localization\TranslatorPanel;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
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


	/** Adds the Tracy bar panel in debug mode. */
	public function afterCompile(ClassType $class): void
	{
		if (!($this->getContainerBuilder()->parameters['debugMode'] ?? false)) {
			return;
		}

		$class->getMethod('initialize')->addBody(sprintf(
			'Tracy\Debugger::getBar()->addPanel(new \%s($this->getService(%s)), %s);',
			TranslatorPanel::class,
			var_export($this->prefix('translator'), true),
			var_export('drago.translator', true),
		));
	}
}
