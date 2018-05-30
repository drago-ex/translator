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

	/**
	 * @var array
	 */
	private $message;

	public function __construct($filename)
	{
		$this->message = $this->parse($filename);
	}

	/**
	 * Processing translate file.
	 * @param string $filename
	 * @return array
	 * @throws Exception
	 */
	private function parse($filename)
	{
		if (!is_file($filename)) {
			throw new Exception('The translation file was not found.');
		}
		return parse_ini_file($filename);
	}

	/**
	 * The translation itself by the key.
	 * @param string $message
	 * @param int $count
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
