<?php

declare(strict_types=1);

namespace Drago\Localization;


class Options
{
	/** @var list<string> Explicit translation directories used as a manual fallback. */
	public array $translateDirs = [];
}
