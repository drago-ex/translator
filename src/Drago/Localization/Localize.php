<?php

/**
 * Drago Translator, extending Nette Framework
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Localization;
use Nette;

/**
 * Settings router for multiple-language website.
 * @author Zdeněk Papučík
 */
class Localize
{
	use Nette\SmartObject;

	/**
	 * Default language.
	 * @var string
	 */
	private $localeDefault;

	/**
	 * List of languages.
	 * @var string
	 */
	private $locales;

	public function __construct($localeDefault, $locales)
	{
		$this->localeDefault = $localeDefault;
		$this->locales = $locales;
	}

	/**
	 * Method for languages route.
	 * @return string
	 */
	public function locale()
	{
		if ($this->localeDefault) {
		    $this->localeDefault = '=' . $this->localeDefault;
		}

		$localization = $this->locales ? $this->localeDefault . ' ' . $this->locales : $this->localeDefault;
		return '[<lang' . $localization . '>/]';
	}

}
