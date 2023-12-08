<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Localization;

use Nette;
use Nette\Neon\Exception;
use Nette\Neon\Neon;


/**
 * Simple translator.
 */
class Translator implements Nette\Localization\Translator
{
	private array $message;


	public function __construct(
		public string $translateDir,
	) {
	}


	/**
	 * @throws Exception
	 */
	private function decodeFile(string $file): array
	{
		$arr = [];
		if (is_file($file)) {
			$arr = Neon::decodeFile($file);
		}
		return $arr;
	}


	/**
	 * @throws Exception
	 */
	public function setTranslate(string $translate): array
	{
		$file = $this->translateDir . '/' . $translate . '.neon';
		$this->message = $this->decodeFile($file);
		return $this->message;
	}


	/**
	 * @throws Exception
	 */
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
