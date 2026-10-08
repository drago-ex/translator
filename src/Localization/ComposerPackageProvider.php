<?php

declare(strict_types=1);

namespace Localization;


interface ComposerPackageProvider
{
	/**
	 * @return array{
	 *     root: array{name: string, install_path: string},
	 *     versions: array<string, array{install_path?: string}>
	 * }
	 */
	public function getData(): array;
}
