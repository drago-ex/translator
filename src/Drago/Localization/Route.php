<?php

/**
 * Drago Translator, extending Nette Framework
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago\Localization;
use Nette;

/**
 * Settings the language for multiple-language website.
 * @author Zdeněk Papučík
 */
class Route
{
	use Nette\SmartObject;

	/**
	 * Method for languages route.
	 * @param  string
	 * @param  string
	 * @return string
	 */
	public static function locale($locale, $locales)
	{
		if ($locale) {
			$locale = '=' . $locale;
		}

		$localization = $locales ? $locale . ' ' . $locales : $locale;
		return '[<lang' . $localization . '>/]';
	}

}
