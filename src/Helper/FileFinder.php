<?php

declare(strict_types = 1);

namespace YamlStandardiser\Helper;

use Symfony\Component\Finder\Finder;

class FileFinder
{

	public function findFilesForPaths(array $paths): array
	{
		$files = [];
		foreach ($paths as $path) {
			if (!file_exists($path)) {
				continue;
			} elseif (is_file($path)) {
				$files[] = $path;
			} else {
				$finder = new Finder();
				$finder->followLinks();
				foreach ($finder->files()->name('*.{yml,yaml}')->sortByName()->in($path) as $fileInfo) {
					$files[] = $fileInfo->getPathname();
				}
			}
		}

		sort($files);

		return array_unique($files);
	}

}
