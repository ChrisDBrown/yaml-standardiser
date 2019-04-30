<?php

declare(strict_types = 1);

namespace YamlStandardiser\Analyser;

use YamlStandardiser\Result\CheckTypesInterface;
use YamlStandardiser\Result\Result;

class OrderAnalyser implements \YamlStandardiser\Analyser\AnalyserInterface
{

	public function analyse(array $yaml): Result
	{
		$sorted = $yaml;
		ksort($sorted);

		$unsortedKeys = array_keys($yaml);
		$sortedKeys = array_keys($sorted);
		if ($unsortedKeys !== $sortedKeys) {
			return new Result(
				false,
				CheckTypesInterface::TYPE_ORDER,
				'Top level keys are not ordered alphabetically.'
			);
		}

		return new Result(true, CheckTypesInterface::TYPE_ORDER);
	}

}
