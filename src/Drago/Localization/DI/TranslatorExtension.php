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
 * Nette DI extension for registering the Translator service.
 *
 * Allows configuration of an optional module-specific
 * translation directory.
 */
class TranslatorExtension extends CompilerExtension
{
	private string $translateDir;


	/**
	 * @param string $translateDir Base translation directory
	 */
	public function __construct(string $translateDir)
	{
		$this->translateDir = $translateDir;
	}


	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'moduleLocaleDir' => Expect::string()->nullable(),
		]);
	}


	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$options = (new Processor)->process(
			Expect::from(new Options),
			$this->config,
		);

		$builder->addDefinition($this->prefix('translator'))
			->setFactory(Translator::class, [$this->translateDir, $options]);
	}
}
