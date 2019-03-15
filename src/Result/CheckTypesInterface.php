<?php

declare(strict_types = 1);

namespace YamlStandardiser\Result;

final class CheckTypesInterface
{

	public const TYPE_VALID = 'valid';

	public const TYPE_ORDER = 'order';

	public const POSSIBLE_TYPES = [
		self::TYPE_VALID,
		self::TYPE_ORDER,
	];

}
