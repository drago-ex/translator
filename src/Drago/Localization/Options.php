<?php

declare(strict_types=1);

namespace Drago\Localization;


/** Translator configuration options. */
class Options
{
	/** Enables automatic scanning of all translation files in the app directory. */
	public bool $autoFinder = true;

	/** @var list<string> */
	public array $translateDirs = [];
}
