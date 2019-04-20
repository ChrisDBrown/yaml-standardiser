<?php

declare(strict_types = 1);

namespace YamlStandardiser\Result;

class ResultsTest extends \PHPUnit\Framework\TestCase
{

	public function testGetIteratorWithFileResults()
	{
		$results = new Results(new FileResults('file.yaml'));

		$this->assertInstanceOf(\ArrayIterator::class, $results->getIterator());
	}

	public function testGetIteratorWithoutFileResults()
	{
		$results = new Results();

		$this->assertInstanceOf(\ArrayIterator::class, $results->getIterator());
	}

	public function testCountWithResults()
	{
		$results = new Results(new FileResults('file.yaml'));

		$this->assertEquals(1, count($results));
	}

	public function testCountWithoutResults()
	{
		$results = new Results();

		$this->assertEquals(0, count($results));
	}

	public function testAddResultWithValidResult()
	{
		$results = new Results();
		$fileResult = new FileResults('file.yaml');
		$results->addFileResults($fileResult);

		$this->assertEquals([$fileResult], $results->toArray());
	}

	public function testAddResultWithInvalidResult()
	{
		$results = new Results();

		$this->expectException(\TypeError::class);

		$results->addFileResults('not a file result');
	}

	public function testFirstWithResults()
	{
		$fileResult = new FileResults('file.yaml');
		$results = new Results($fileResult);

		$this->assertEquals($fileResult, $results->first());
	}

	public function testFirstWithoutResults()
	{
		$results = new Results();

		$this->assertEquals(null, $results->first());
	}

	public function testHasErrorsWithErrors()
	{
		$results = new Results(
			new FileResults(
				'file.yml',
				new Result(true, CheckTypesInterface::TYPE_VALID),
				new Result(false, CheckTypesInterface::TYPE_ORDER)
			)
		);

		$this->assertEquals(true, $results->hasErrors());
	}

	public function testHasErrorsWithoutErrors()
	{
		$results = new Results(
			new FileResults(
				'file.yml',
				new Result(true, CheckTypesInterface::TYPE_VALID),
				new Result(true, CheckTypesInterface::TYPE_ORDER)
			)
		);

		$this->assertEquals(false, $results->hasErrors());
	}

	public function testHasErrorsWithoutResults()
	{
		$results = new Results();

		$this->assertEquals(false, $results->hasErrors());
	}

	public function testGetErrorsWithErrors()
	{
		$errorResult = new Result(false, CheckTypesInterface::TYPE_ORDER);
		$errorFileResults = new FileResults('badfile.yml', $errorResult);

		$results = new Results(
			new FileResults(
				'goodfile.yml',
				new Result(true, CheckTypesInterface::TYPE_VALID)
			),
			$errorFileResults
		);

		$this->assertEquals([$errorFileResults], $results->getErrors()->toArray());
	}

	public function testGetErrorsWithoutErrors()
	{
		$results = new Results(
			new FileResults(
				'goodfile.yml',
				new Result(true, CheckTypesInterface::TYPE_VALID),
				new Result(true, CheckTypesInterface::TYPE_ORDER)
			),
			new FileResults(
				'betterfile.yml',
				new Result(true, CheckTypesInterface::TYPE_VALID),
				new Result(true, CheckTypesInterface::TYPE_ORDER)
			)
		);

		$this->assertEquals(false, $results->hasErrors());
	}

	public function testGetErrorsWithoutResults()
	{
		$results = new Results();

		$this->assertEquals([], $results->getErrors()->toArray());
	}

}
