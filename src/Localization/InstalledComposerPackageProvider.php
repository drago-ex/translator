<?php

declare(strict_types=1);

namespace Localization;

use Composer\InstalledVersions;


class InstalledComposerPackageProvider implements ComposerPackageProvider
{
	/**
	 * @return array{
	 *     root: array{name: string, install_path: string},
	 *     versions: array<string, array{install_path?: string}>
	 * }
	 */
	public function getData(): array
	{
		$root = InstalledVersions::getRootPackage();
		foreach (InstalledVersions::getAllRawData() as $data) {
			if ($data['root']['name'] !== $root['name']) {
				continue;
			}

			$versions = [];
			foreach ($data['versions'] as $packageName => $package) {
				if (isset($package['install_path'])) {
					$versions[$packageName] = [
						'install_path' => $package['install_path'],
					];
				}
			}

			return [
				'root' => [
					'name' => $root['name'],
					'install_path' => $root['install_path'],
				],
				'versions' => $versions,
			];
		}

		return [
			'root' => [
				'name' => $root['name'],
				'install_path' => $root['install_path'],
			],
			'versions' => [],
		];
	}
}
