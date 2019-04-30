<?php

declare(strict_types = 1);

namespace YamlStandardiser\Fixer;

interface FixerInterface
{

	public function fix(string $yaml, array $config = []): string;

}
