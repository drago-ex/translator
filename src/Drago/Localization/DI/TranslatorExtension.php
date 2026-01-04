<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Localization\DI;

use Drago\Localization\Options;
use Drago\Localization\Translator;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;


/**
 * DI extension for registering the Translator service.
 * Supports global and optional module-specific translation directories.
 */
class TranslatorExtension extends CompilerExtension
{
	/** @var string Path to the main translation directory */
	private string $translateDir;


	/**
	 * @param string $translateDir Path to the main translation directory
	 */
	public function __construct(string $translateDir)
	{
		$this->translateDir = $translateDir;
	}


	/**
	 * Defines the structure of the extension configuration.
	 *
	 * @return Schema Expected configuration structure
	 */
	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'moduleDir' => Expect::string()->nullable(), // Optional module-specific translations
		]);
	}


	/**
	 * Registers the Translator service in the DI container.
	 * Adds both the global and optional module translation directories.
	 */
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		// Normalize config into Options object
		$processor = new Processor;
		$options = $processor->process(
			Expect::from(new Options),
			$this->config,
		);

		// Register Translator service
		$builder->addDefinition($this->prefix('translator'))
			->setFactory(Translator::class, [$this->translateDir, $options]);
	}
}
