<?php

declare(strict_types = 1);

namespace YamlStandardiser\Result;

class Results implements \IteratorAggregate, \Countable
{

	private $results;

	public function __construct(Result ...$results)
	{
		$this->results = $results;
	}

	public function getIterator(): \Iterator
	{
		return new \ArrayIterator($this->results);
	}

	public function count()
	{
		return count($this->results);
	}

	public function addResult(Result $result): void
	{
		$this->results[] = $result;
	}

	public function first(): ?Result
	{
		if (!isset($this->results[0])) {
			return null;
		}

		return $this->results[0];
	}

	public function hasErrors(): bool
	{
		foreach ($this->results as $result) {
			if (!$result->wasSuccess()) {
				return true;
			}
		}

		return false;
	}

	public function getErrors(): Results
	{
		$errorResults = [];

		foreach ($this->results as $result) {
			if (!$result->wasSuccess()) {
				$errorResults[] = $result;
			}
		}

		return new Results(...$errorResults);
	}

	public function toArray(): array
	{
		return $this->results;
	}

}
