<?php

/**
 * This file is part of the Drago Framework
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Localization;
use Nette;

/**
 * Translator adapter.
 * @author Zdeněk Papučík
 */
class Translator implements Nette\Localization\ITranslator
{
	/**
	 * List of words for translation.
	 * @var array
	 */
	private $message;

	public function __construct($message)
	{
		$this->message = $message;
	}

	/**
	 * Translates the given string.
	 * @param  string
	 * @param  int
	 * @return string
	 */
	public function translate($message, $count = NULL)
	{
		if ($count === NULL) {
			$count = 1;
		}

		if (isset($this->message[$message])) {
			return $this->message[$message];
		}

		return $message;
	}

}
