<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Localization\DI;

use Drago\Localization\Options;
use Drago\Localization\Translator;
use Drago\Localization\TranslatorFinder;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;


/**
 * Nette DI extension for registering the Translator service.
 *
 * Allows configuration of an optional module-specific
 * translation directory.
 */
class TranslatorExtension extends CompilerExtension
{
	private string $appDir;
	private string $tempDir;


	/**
	 * @param string $appDir  Base application directory (%appDir%)
	 * @param string $tempDir Temp directory for caching (%tempDir%)
	 */
	public function __construct(string $appDir, string $tempDir)
	{
		$this->appDir = $appDir;
		$this->tempDir = $tempDir;
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
