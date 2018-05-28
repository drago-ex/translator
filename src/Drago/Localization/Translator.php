<?php

// Enable strict mode.
declare(strict_types = 1);

/**
 * Drago Translator
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Localization;

use Nette;
use Exception;

/**
 * Simple translator.
 */
class Translator implements Nette\Localization\ITranslator
{
	use Nette\SmartObject;

	/**
	 * @var array
	 */
	private $message;

	public function __construct(string $filename)
	{
		$this->message = $this->parse($filename);
	}

	/**
	 * Processing translate file.
	 */
	private function parse(string $filename): array
	{
		if (!is_file($filename)) {
			throw new Exception('The translation file was not found.');
		}
		return parse_ini_file($filename);
	}

	/**
	 * The translation itself by the key.
	 */
	public function translate(string $message, int $count = null): string
	{
		if ($count === null) {
			$count = 1;
		}
		return isset($this->message[$message]) ? $this->message[$message] : $message;
	}

}
