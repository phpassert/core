<?php

require_once('bootstrap.php');
use Symfony\Component\Console\Output\ConsoleOutput;
use PHPAssert\Core\Discoverer\FSDiscoverer;
use PHPAssert\Core\Reporter\ConsoleReporter;
use PHPAssert\Core\Runner\Runner;

$discoverer = new FSDiscoverer(__DIR__ . DIRECTORY_SEPARATOR . 'examples');

$output = new ConsoleOutput();
$reporter = new ConsoleReporter($output);
$runner = new Runner($discoverer, $reporter);

$output->writeln('start executing tests');
$runner->run();
$output->writeln('');
$output->writeln('done running tests');
