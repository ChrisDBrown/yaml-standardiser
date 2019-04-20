<?php

declare(strict_types = 1);

namespace YamlStandardiser\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use YamlStandardiser\Analyser\OrderAnalyser;
use YamlStandardiser\Result\CheckTypesInterface;
use YamlStandardiser\Result\FileResults;
use YamlStandardiser\Result\Result;

class StandardiserCommand extends \Symfony\Component\Console\Command\Command
{

	private const NAME = 'standardiser';

	private const ARGUMENT_FILEPATH = 'filepath';

	protected function configure(): void
	{
		$this->setName(self::NAME)
			->addArgument(
				self::ARGUMENT_FILEPATH,
				InputArgument::REQUIRED,
				'Path to file to test'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$filepath = strval($input->getArgument(self::ARGUMENT_FILEPATH));

		$results = new FileResults($filepath);

		$parsed = '';

		try {
			$parsed = Yaml::parseFile($filepath);
		} catch (\Symfony\Component\Yaml\Exception\ParseException $e) {
			$result = new Result(
				false,
				CheckTypesInterface::TYPE_VALID,
				$e->getMessage()
			);

			$results->addResult($result);
		}

		/**
		 * TODO: Result writer service rather than var_dump
		 * TODO: Don't just blow up here once this is handling multiple files
		 */
		if ($results->hasErrors()) {
			var_dump($results->first());

			return 1;
		}

		$analyser = new OrderAnalyser();
		$result = $analyser->analyse($parsed);

		if (!$result->wasSuccess()) {
			$output->writeln(sprintf(
				'<error>Failure: %s</error>',
				$result->getMessage()
			));

			return 1;
		}

		/**
		 * TODO: Create analyser collection service, register all analysers against it and run them
		 * Should create a result collection and add each result returned from the analyser to it
		 * Result collection should have an overall pass/fail status and only accept result objects
		 */

		$output->writeln('<info>Complete</info>');

		return 0;
	}

}
