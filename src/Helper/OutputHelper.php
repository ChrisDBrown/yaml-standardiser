<?php

declare(strict_types = 1);

namespace YamlStandardiser\Helper;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use YamlStandardiser\Result\Results;

class OutputHelper
{

	private $style;

	public function __construct(InputInterface $input, OutputInterface $output)
	{
		$this->style = new SymfonyStyle($input, $output);
	}

	public function error(string $ln): void
	{
		$this->style->error($ln);
	}

	public function printResults(Results $results): void
	{
		if (!$results->hasErrors()) {
			$this->style->success(sprintf('%d files tested without issues', $results->count()));

			return;
		}

		$errorResults = $results->getErrors();

		foreach ($errorResults as $fileErrorResult) {
			$this->style->title(
				sprintf('File %s has %d errors', $fileErrorResult->getFilepath(), $fileErrorResult->count())
			);
			$errorRows = [];
			foreach ($fileErrorResult as $errorResult) {
				$errorRows[] = [
					$errorResult->getCheckType(),
					$errorResult->getMessage(),
					$errorResult->isAutoFixable() ? '   ✔' : '   ✘', // spaces to cheat center the mark in the table
				];
			}

			$this->style->table(['Type', 'Message', 'Fixable'], $errorRows);
		}

		$this->style->error(sprintf('%d files have errors', $errorResults->count()));
	}

}
