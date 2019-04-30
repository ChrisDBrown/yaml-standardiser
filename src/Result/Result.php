<?php

declare(strict_types = 1);

namespace YamlStandardiser\Result;

final class Result
{

	private $wasSuccess;

	private $checkType;

	private $message;

	private $isAutoFixable;

	public function __construct(
		bool $wasSuccess,
		string $checkType,
		string $message = '',
		bool $isAutoFixable = false
	)
	{
		$this->wasSuccess = $wasSuccess;
		$this->setCheckType($checkType);
		$this->message = $message;
		$this->isAutoFixable = $isAutoFixable;
	}

	public function wasSuccess(): bool
	{
		return $this->wasSuccess;
	}

	public function setCheckType(string $checkType): void
	{
		if (!in_array($checkType, CheckTypesInterface::POSSIBLE_TYPES)) {
			throw new \InvalidArgumentException(
				sprintf('Check type %s given, which is not a valid check type', $checkType)
			);
		}

		$this->checkType = $checkType;
	}

	public function getCheckType(): string
	{
		return $this->checkType;
	}

	public function getMessage(): string
	{
		return $this->message;
	}

	public function isAutoFixable(): bool
	{
		return $this->isAutoFixable;
	}

}
