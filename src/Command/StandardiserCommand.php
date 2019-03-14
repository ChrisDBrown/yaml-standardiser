<?php

declare(strict_types = 1);

namespace YamlStandardiser\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class StandardiserCommand extends \Symfony\Component\Console\Command\Command
{

	private const NAME = 'standardiser';

	private const ARGUMENT_FILEPATH = 'filepath';

	private const OPTION_DRY_RUN = 'dry-run';

	protected function configure(): void
	{
		$this->setName(self::NAME)
			->addArgument(
				self::ARGUMENT_FILEPATH,
				InputArgument::REQUIRED,
				'Path to file to test'
			)
			->addOption(
				self::OPTION_DRY_RUN,
				'd',
				InputOption::VALUE_OPTIONAL,
				'Analyse file without editing it',
				false
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$filepath = strval($input->getArgument(self::ARGUMENT_FILEPATH));
		$isDryRun = (bool) $input->getOption(self::OPTION_DRY_RUN);

		if (!is_readable($filepath)) {
			$output->writeln(sprintf('<error>File %s is unreadable</error>', $filepath));

			return 1;
		}

		if (!$isDryRun && !is_writable($filepath)) {
			$output->writeln(sprintf(
				'<error>Cannot write to %s, so errors cannot be corrected</error>',
				$filepath
			));

			return 1;
		}

		try {
			$parsed = Yaml::parseFile($filepath);
			$sorted = $parsed;
			ksort($sorted);

			if (Yaml::dump($parsed) !== Yaml::dump($sorted)) {
				$output->writeln(sprintf('<error>File %s is not alphabetically sorted</error>', $filepath));

				if ($isDryRun) {
					return 1;
				}

				$output->writeln('This error will be resolved automatically');

				file_put_contents($filepath, Yaml::dump($sorted));
			}
		} catch (\Symfony\Component\Yaml\Exception\ParseException $e) {
			$output->writeln(sprintf(
				'<error>File %s could not be parsed: %s',
				$filepath,
				$e->getMessage()
			));

			return 1;
		}

		$output->writeln('<info>Complete</info>');

		return 0;
	}

}
