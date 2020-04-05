<?php

declare(strict_types = 1);

use Drago\Localization;

class Presenter extends Nette\Application\UI\Presenter
{
	use Localization\TranslatorAdapter;
}
