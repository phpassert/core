<?php

require_once('bootstrap.php');
use Symfony\Component\Console\Output\ConsoleOutput;
use PHPAssert\Core\Discoverer\FSDiscoverer;
use PHPAssert\Core\Reporter\ConsoleReporter;
use PHPAssert\Core\Runner\Runner;

$discoverer = new FSDiscoverer(__DIR__ . DIRECTORY_SEPARATOR . 'examples');
$reporter = new ConsoleReporter(new ConsoleOutput());
$runner = new Runner($discoverer, $reporter);

$runner->run();

