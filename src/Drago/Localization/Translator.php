<?php

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

	// Exception message.
	const FILE_NOT_FOUND = 'The translation file was not found.';

	/**
	 * @var array
	 */
	private $message;

	public function __construct($filename)
	{
		$this->message = $this->parse($filename);
	}

	/**
	 * @param string
	 * @return array
	 */
	private function parse($filename)
	{
		if (!is_file($filename)) {
			throw new Exception(self::FILE_NOT_FOUND);
		}
		return parse_ini_file($filename);
	}

	/**
	 * Translates the given string.
	 * @param string
	 * @param int
	 * @return string
	 */
	public function translate($message, $count = null)
	{
		if ($count === null) {
			$count = 1;
		}
		return isset($this->message[$message]) ? $this->message[$message] : $message;
	}

}
