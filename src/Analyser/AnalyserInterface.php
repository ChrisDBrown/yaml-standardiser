<?php

declare(strict_types = 1);

namespace YamlStandardiser\Analyser;

use YamlStandardiser\Result\Result;

interface AnalyserInterface
{

	public function analyse(array $yaml): Result;

}
