<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Localization\DI;

use Drago\Localization\Translator;
use Nette\DI\CompilerExtension;


/**
 * Register services.
 */
class TranslatorExtension extends CompilerExtension
{
	/** @var string */
	private $translateDir;


	public function __construct(string $translateDir)
	{
		$this->translateDir = $translateDir;
	}


	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('translator'))
			->setFactory(Translator::class, [$this->translateDir]);
	}
}
