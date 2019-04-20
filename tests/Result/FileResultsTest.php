<?php

declare(strict_types = 1);

namespace YamlStandardiser\Result;

class FileResultsTest extends \PHPUnit\Framework\TestCase
{

	public function testGetIteratorWithResults()
	{
		$firstResult = new Result(true, CheckTypesInterface::TYPE_VALID);
		$secondResult = new Result(true, CheckTypesInterface::TYPE_ORDER);

		$fileResults = new FileResults(
			'file.yaml',
			$firstResult,
			$secondResult
		);

		$iterator = $fileResults->getIterator();

		$this->assertInstanceOf(\ArrayIterator::class, $iterator);
	}

	public function testGetIteratorWithoutResults()
	{
		$fileResults = new FileResults(
			'file.yaml'
		);

		$iterator = $fileResults->getIterator();

		$this->assertInstanceOf(\ArrayIterator::class, $iterator);
	}

	public function testCountWithResults()
	{
		$firstResult = new Result(true, CheckTypesInterface::TYPE_VALID);
		$secondResult = new Result(true, CheckTypesInterface::TYPE_ORDER);

		$fileResults = new FileResults(
			'file.yaml',
			$firstResult,
			$secondResult
		);

		$this->assertEquals(2, count($fileResults));
	}

	public function testCountWithoutResults()
	{
		$fileResults = new FileResults(
			'file.yaml'
		);

		$this->assertEquals(0, count($fileResults));
	}

	public function testAddResultWithValidResult()
	{
		$fileResults = new FileResults(
			'file.yaml'
		);

		$result = new Result(true, CheckTypesInterface::TYPE_VALID);

		$fileResults->addResult($result);

		$this->assertEquals([$result], $fileResults->toArray());
	}

	public function testAddResultWithInvalidResult()
	{
		$fileResults = new FileResults(
			'file.yaml'
		);

		$this->expectException(\TypeError::class);

		$fileResults->addResult('a string, not a result');
	}

	public function testFirstWithResults()
	{
		$result = new Result(true, CheckTypesInterface::TYPE_VALID);
		$fileResults = new FileResults(
			'file.yaml',
			$result
		);

		$this->assertEquals($result, $fileResults->first());
	}

	public function testFirstWithoutResults()
	{
		$fileResults = new FileResults(
			'file.yaml'
		);

		$this->assertEquals(null, $fileResults->first());
	}

	public function testHasErrorsWithErrors()
	{
		$fileResults = new FileResults(
			'file.yaml',
			new Result(true, CheckTypesInterface::TYPE_VALID),
			new Result(false, CheckTypesInterface::TYPE_ORDER)
		);

		$this->assertEquals(true, $fileResults->hasErrors());
	}

	public function testHasErrorsWithoutErrors()
	{
		$fileResults = new FileResults(
			'file.yaml',
			new Result(true, CheckTypesInterface::TYPE_VALID),
			new Result(true, CheckTypesInterface::TYPE_ORDER)
		);

		$this->assertEquals(false, $fileResults->hasErrors());
	}

	public function testHasErrorsWithoutResults()
	{
		$fileResults = new FileResults(
			'file.yaml'
		);

		$this->assertEquals(false, $fileResults->hasErrors());
	}

	public function testGetErrorsWithErrors()
	{
		$errorResult = new Result(false, CheckTypesInterface::TYPE_ORDER);

		$fileResults = new FileResults(
			'file.yaml',
			new Result(true, CheckTypesInterface::TYPE_VALID),
			$errorResult
		);

		$this->assertEquals([$errorResult], $fileResults->getErrors()->toArray());
	}

	public function testGetErrorsWithoutErrors()
	{
		$fileResults = new FileResults(
			'file.yaml',
			new Result(true, CheckTypesInterface::TYPE_VALID),
			new Result(true, CheckTypesInterface::TYPE_ORDER)
		);

		$this->assertEquals([], $fileResults->getErrors()->toArray());
	}

	public function testGetErrorsWithoutResults()
	{
		$fileResults = new FileResults(
			'file.yaml'
		);

		$this->assertEquals([], $fileResults->getErrors()->toArray());
	}

}
