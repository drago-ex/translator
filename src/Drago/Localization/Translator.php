<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Localization;

use Nette\Localization\ITranslator;
use Nette\SmartObject;


/**
 * Simple translator.
 */
class Translator implements ITranslator
{
	use SmartObject;

	/** @var array */
	private $message;

	/** @var string */
	private $translateDir;


	public function __construct(string $translateDir)
	{
		$this->translateDir = $translateDir;
	}


	/**
	 * @throws \Exception
	 */
	public function setTranslate(string $translate): array
	{
		$file = $this->translateDir . '/' . $translate . '.ini';
		$this->message = parse_ini_file($file);
		return $this->message;
	}


	/**
	 * Translates the given string.
	 */
	public function translate($message, ...$parameters): string
	{
		return isset($this->message[$message])
			? $this->message[$message]
			: $message;
	}
}
