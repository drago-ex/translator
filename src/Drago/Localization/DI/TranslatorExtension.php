<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Localization\DI;

use Drago\Localization\Translator;
use Nette\DI\CompilerExtension;


/**
 * CompilerExtension for registering the Translator service in the DI container.
 * This extension allows the injection of a custom translation directory for the Translator service.
 */
class TranslatorExtension extends CompilerExtension
{
	/** @var string The directory where translation files are stored. */
	private string $translateDir;


	/**
	 * @param string $translateDir The directory containing translation files.
	 */
	public function __construct(string $translateDir)
	{
		$this->translateDir = $translateDir;
	}


	/**
	 * Loads the configuration for the Translator service into the container.
	 * Registers the Translator service with the provided translation directory.
	 */
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		// Register the Translator service with the translation directory
		$builder->addDefinition($this->prefix('translator'))
			->setFactory(Translator::class, [$this->translateDir]);
	}
}
