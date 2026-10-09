<?php

declare(strict_types=1);

namespace Drago\Localization\DI;

use Composer\InstalledVersions;
use Drago\Localization\ComposerTranslationFinder;
use Drago\Localization\InstalledComposerPackageProvider;
use Drago\Localization\Options;
use Drago\Localization\Translator;
use Drago\Localization\TranslatorFinder;
use Drago\Localization\TranslatorPanel;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use ReflectionClass;


class TranslatorExtension extends CompilerExtension
{
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

		// Composer metadata is read once while the container is compiled.
		$directories = (new ComposerTranslationFinder(new InstalledComposerPackageProvider))->findDirectories();
		$this->watchInstalledPackages();

		$builder->addDefinition($this->prefix('finder'))
			->setFactory(TranslatorFinder::class, [
				'composerDirectories' => $directories,
				'debugMode' => (bool) ($builder->parameters['debugMode'] ?? false),
			]);

		$builder->addDefinition($this->prefix('translator'))
			->setFactory(Translator::class, [$options, $this->prefix('@finder')]);
	}


	/** Recompiles the container when installed Composer packages change. */
	private function watchInstalledPackages(): void
	{
		$file = (new ReflectionClass(InstalledVersions::class))->getFileName();
		if (is_string($file) && is_file($installed = dirname($file) . '/installed.php')) {
			$this->getContainerBuilder()->addDependency($installed);
		}
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
