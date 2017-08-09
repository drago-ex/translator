<?php

/**
 * Extending for Nette Framework
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Localization;

use Nette;
use Exception;

/**
 * Little translator.
 */
class Translator implements Nette\Localization\ITranslator
{
	use Nette\SmartObject;

	/**
	 * @var array
	 */
	private $message;

	public function __construct($filename)
	{
		$this->message = $this->parse($filename);
	}

	/**
	 * Parse ini file.
	 * @param string
	 * @return array
	 */
	private function parse($filename)
	{
		if (!is_file($filename)) {
			throw new Exception('Missing translation file in ' . $filename);
		}
		return parse_ini_file($filename);
	}

	/**
	 * Translates the given string.
	 * @param string
	 * @param int
	 * @return string
	 */
	public function translate($message, $count = NULL)
	{
		if ($count === NULL) {
			$count = 1;
		}
		return isset($this->message[$message]) ? $this->message[$message] : $message;
	}

}
