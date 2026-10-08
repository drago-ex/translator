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


class TranslatorExtension extends CompilerExtension
{
	private ?Options $options = null;

	/** @var list<string> */
	private array $additionalTranslateDirs = [];


	public function addTranslateDir(string $dir): void
	{
		if (!in_array($dir, $this->additionalTranslateDirs, true)) {
			$this->additionalTranslateDirs[] = $dir;
		}

		$options = $this->options;
		if ($options !== null && !in_array($dir, $options->translateDirs, true)) {
			$options->translateDirs[] = $dir;
		}
	}


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
			'exclude' => Expect::arrayOf(Expect::string())->default([]),
		]);
	}


	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$options = (new Processor)->process(
			Expect::from(new Options),
			$this->config,
		);
		$this->options = $options;

		foreach ($this->additionalTranslateDirs as $dir) {
			if (!in_array($dir, $options->translateDirs, true)) {
				$options->translateDirs[] = $dir;
			}
		}

		// Register TranslationFinder service.
		$builder->addDefinition($this->prefix('finder'))
			->setFactory(TranslatorFinder::class, [$this->appDir, $this->tempDir]);

		// Register Translator service.
		$builder->addDefinition($this->prefix('translator'))
			->setFactory(Translator::class, [$options, $this->prefix('@finder')]);
	}
}
