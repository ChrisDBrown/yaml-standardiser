<?php

declare(strict_types = 1);

namespace YamlStandardiser\Result;

class Results implements \IteratorAggregate, \Countable
{

	private $fileResults;

	public function __construct(FileResults ...$fileResults)
	{
		$this->fileResults = $fileResults;
	}

	public function getIterator(): \Iterator
	{
		return new \ArrayIterator($this->fileResults);
	}

	public function count()
	{
		return count($this->fileResults);
	}

	public function addFileResults(FileResults $fileResults): void
	{
		$this->fileResults[] = $fileResults;
	}

	public function first(): ?FileResults
	{
		if (!isset($this->fileResults[0])) {
			return null;
		}

		return $this->fileResults[0];
	}

	public function hasErrors(): bool
	{
		foreach ($this->fileResults as $fileResult) {
			if ($fileResult->hasErrors()) {
				return true;
			}
		}

		return false;
	}

	public function getErrors(): Results
	{
		$errorResults = [];

		foreach ($this->fileResults as $fileResult) {
			if ($fileResult->hasErrors()) {
				$errorResults[] = new FileResults($fileResult->getFilepath(), ...$fileResult->getErrors());
			}
		}

		return new Results(...$errorResults);
	}

	public function toArray(): array
	{
		return $this->fileResults;
	}

}
