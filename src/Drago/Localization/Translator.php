<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Localization;

use Nette;
use Nette\Neon\Neon;


/**
 * Simple translator.
 */
class Translator implements Nette\Localization\Translator
{
	use Nette\SmartObject;

	private array $message;


	public function __construct(
		public string $translateDir,
	) {
	}


	private function decodeFile(string $file): array
	{
		$arr = [];
		if (is_file($file)) {
			$arr = Neon::decodeFile($file);
		}
		return $arr;
	}


	public function setTranslate(string $translate): array
	{
		$file = $this->translateDir . '/' . $translate . '.neon';
		$this->message = $this->decodeFile($file);
		return $this->message;
	}


	public function setCustomTranslate(string $path, string $translate): array
	{
		$file = $path . $translate . '.neon';
		$this->message = $this->decodeFile($file);
		return $this->message;
	}


	/**
	 * Translates the given string.
	 */
	public function translate(mixed $message, mixed ...$parameters): string
	{
		return $this->message[$message] ?? $message;
	}
}
