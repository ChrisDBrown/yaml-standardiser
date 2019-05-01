<?php

declare(strict_types = 1);

namespace YamlStandardiser\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use YamlStandardiser\Analyser\OrderAnalyser;
use YamlStandardiser\Helper\FileFinderHelper;
use YamlStandardiser\Helper\OutputHelper;
use YamlStandardiser\Result\CheckTypesInterface;
use YamlStandardiser\Result\FileResults;
use YamlStandardiser\Result\Result;
use YamlStandardiser\Result\Results;

class StandardiserCommand extends \Symfony\Component\Console\Command\Command
{

	private const NAME = 'standardiser';

	private const ARGUMENT_FILEPATHS = 'filepaths';

	protected function configure(): void
	{
		$this->setName(self::NAME)
			->addArgument(
				self::ARGUMENT_FILEPATHS,
				InputArgument::IS_ARRAY,
				'Path to file to test'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$filepaths = $input->getArgument(self::ARGUMENT_FILEPATHS);
		$outputHelper = new OutputHelper($input, $output);

		if (!is_array($filepaths) || count($filepaths) === 0) {
			$outputHelper->error('No filepaths passed to command');

			return 1;
		}

		$fileFinder = new FileFinderHelper();
		$matchingFiles = $fileFinder->findFilesForPaths($filepaths);

		if (count($matchingFiles) === 0) {
			$outputHelper->error('No files found for filepaths given');

			return 1;
		}

		$results = new Results();

		foreach ($matchingFiles as $file) {
			$fileResults = new FileResults($file);

			$parsed = '';

			try {
				$parsed = Yaml::parseFile($file);
			} catch (\Symfony\Component\Yaml\Exception\ParseException $e) {
				$fileResults->addResult(new Result(
					false,
					CheckTypesInterface::TYPE_VALID,
					$e->getMessage()
				));

				$results->addFileResults($fileResults);

				continue;
			}

			$analyser = new OrderAnalyser();
			$fileResults->addResult($analyser->analyse($parsed));

			$results->addFileResults($fileResults);
		}

		$outputHelper->printResults($results);

		if ($results->hasErrors()) {
			return 1;
		}

		return 0;
	}

}
