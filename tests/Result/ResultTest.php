<?php

declare(strict_types = 1);

namespace YamlStandardiser\Result;

class ResultTest extends \PHPUnit\Framework\TestCase
{

	public function testConstructWithValidCheckTypes()
	{
		$result = new Result(
			true,
			CheckTypesInterface::TYPE_VALID
		);

		$this->assertInstanceOf(\YamlStandardiser\Result\Result::class, $result);
	}

	public function testConstructWithInvalidCheckTypes()
	{
		$this->expectException(\InvalidArgumentException::class);

		new Result(
			true,
			'completely_fake_check_type'
		);
	}

}
