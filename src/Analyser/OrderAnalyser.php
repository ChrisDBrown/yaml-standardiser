<?php

declare(strict_types = 1);

namespace YamlStandardiser\Analyser;

use Symfony\Component\Yaml\Yaml;
use YamlStandardiser\Config\Config;
use YamlStandardiser\Result\CheckTypesInterface;
use YamlStandardiser\Result\Result;

class OrderAnalyser implements \YamlStandardiser\Analyser\AnalyserInterface
{

	private $config;

	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	public function analyse(array $yaml): Result
	{
		$sorted = $this->applyOrderingConfigRules($yaml);

		if (Yaml::dump($yaml) !== Yaml::dump($sorted)) {
			/**
			 * TODO: error reported here is fairly useless - first step to report the first incorrect line?
			 */
			return new Result(
				false,
				CheckTypesInterface::TYPE_ORDER,
				'Keys are not ordered alphabetically.'
			);
		}

		return new Result(true, CheckTypesInterface::TYPE_ORDER);
	}

	private function applyOrderingConfigRules(array $yaml): array
	{
		$sorted = $yaml;
		if ($this->config->getKeyAlphabetisationDepth() > 0) {
			$currentDepth = 0;
			$maxDepth = $this->config->getKeyAlphabetisationDepth();

			$sorted = $this->alphabetiseTree($yaml, $currentDepth, $maxDepth);
		}

		$sorted = $this->sortPrioritisedTopLevelKeys($sorted);

		return $sorted;
	}

	private function alphabetiseTree(array $array, int $currentDepth, int $maxDepth)
	{
		$array = $this->alphabetise($array);
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$currentDepth++;
				if ($currentDepth < $maxDepth) {
					$array[$key] = $this->alphabetiseTree($value, $currentDepth, $maxDepth);
				}
				continue;
			}
		}

		return $array;
	}

	private function alphabetise(array $array): array
	{
		$beginsWithUnderscore = array_filter(
			$array,
			function ($key) {
				return strpos($key, '_') === 0;
			},
			ARRAY_FILTER_USE_KEY
		);

		$theRest = array_filter(
			$array,
			function ($key) {
				return strpos($key, '_') !== 0;
			},
			ARRAY_FILTER_USE_KEY
		);

		ksort($beginsWithUnderscore);
		ksort($theRest);

		return array_merge($beginsWithUnderscore, $theRest);
	}

	private function sortPrioritisedTopLevelKeys(array $yaml): array
	{
		$prioritisedKeys = $this->config->getPrioritisedTopLevelKeys();

		if (count($prioritisedKeys) === 0) {
			return $yaml;
		}

		$keyInPrioritisedArray = array_filter(
			$yaml,
			function ($key) use ($prioritisedKeys) {
				return in_array($key, $prioritisedKeys);
			},
			ARRAY_FILTER_USE_KEY
		);

		if (count($keyInPrioritisedArray)) {
			return $yaml;
		}

		$theRest = array_filter(
			$yaml,
			function ($key) use ($prioritisedKeys) {
				return !in_array($key, $prioritisedKeys);
			},
			ARRAY_FILTER_USE_KEY
		);

		$ordered = [];
		foreach ($prioritisedKeys as $prioritisedKey) {
			if (isset($keyInPrioritisedArray[$prioritisedKey])) {
				$ordered[$prioritisedKey] = $keyInPrioritisedArray[$prioritisedKey];
			}
		}

		return array_merge($ordered, $theRest);
	}

}
