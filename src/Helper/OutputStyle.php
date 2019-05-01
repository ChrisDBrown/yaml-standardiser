<?php

declare(strict_types = 1);

namespace YamlStandardiser\Helper;

use YamlStandardiser\Result\Results;

class OutputStyle extends \Symfony\Component\Console\Style\SymfonyStyle
{

	public function printResults(Results $results): void
	{
		if (!$results->hasErrors()) {
			$this->success(sprintf('%d files tested without issues', $results->count()));

			return;
		}

		$errorResults = $results->getErrors();

		foreach ($errorResults as $fileErrorResult) {
			$this->title(
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

			$this->table(['Type', 'Message', 'Fixable'], $errorRows);
		}

		$this->error(sprintf('%d files have errors', $errorResults->count()));
	}

}
