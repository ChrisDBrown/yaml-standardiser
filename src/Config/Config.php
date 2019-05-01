<?php

declare(strict_types = 1);

namespace YamlStandardiser\Config;

class Config
{

	public function getIndentType(): string
	{
		return 'spaces';
	}

	public function getIndentAmount(): int
	{
		return 2;
	}

	public function getKeyAlphabetisationDepth(): int
	{
		return 2;
	}

	public function getPrioritisedTopLevelKeys(): array
	{
		return ['parameters', 'services'];
	}

	public function getSpaceBetweenBlocksAmount(): int
	{
		return 0;
	}

}
