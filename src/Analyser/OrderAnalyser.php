<?php

declare(strict_types = 1);

namespace YamlStandardiser\Analyser;

use Symfony\Component\Yaml\Yaml;
use YamlStandardiser\Result\CheckTypesInterface;
use YamlStandardiser\Result\Result;

class OrderAnalyser implements \YamlStandardiser\Analyser\AnalyserInterface
{

	public function analyse(array $yaml, string $filepath): Result
	{
		$sorted = $yaml;
		ksort($sorted);

		if (Yaml::dump($yaml) !== Yaml::dump($sorted)) {
			return new Result(
				$filepath,
				false,
				CheckTypesInterface::TYPE_ORDER,
				'File did not pass order test'
			);
		}

		return new Result($filepath, true, CheckTypesInterface::TYPE_ORDER);
	}

}
