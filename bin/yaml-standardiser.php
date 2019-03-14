<?php
declare(strict_types=1);

use Symfony\Component\Console\Application;
use YamlStandardiser\Command\StandardiserCommand;

$composerAutoloadFile = __DIR__ . '/../vendor/autoload.php';
if (!is_file($composerAutoloadFile)) {
	$composerAutoloadFile = __DIR__ . '/../../../autoload.php';
}

require_once $composerAutoloadFile;

$standardiser = new StandardiserCommand();
$application = new Application('Yaml Standardiser');
$application->setCatchExceptions(false);
$application->add($standardiser);
$application->setDefaultCommand($standardiser->getName(), true);
$application->run();
