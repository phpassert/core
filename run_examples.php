<?php

require_once('bootstrap.php');
use Symfony\Component\Console\Output\ConsoleOutput;
use PHPAssert\Core\Discoverer\FSDiscoverer;
use PHPAssert\Core\Reporter\ConsoleReporter;
use PHPAssert\Core\Runner\Runner;
use PHPAssert\Core\Result\Result;

$discoverer = new FSDiscoverer(__DIR__ . DIRECTORY_SEPARATOR . 'examples');

$output = new ConsoleOutput();
$reporter = new ConsoleReporter($output);
$runner = new Runner($discoverer, $reporter);

$output->writeln('start executing tests');
$results = $runner->run();
$failed = array_filter($results, function(Result $result) {
    return !$result->isSuccess();
});

$failedAmount = count($failed);

$output->writeln('');
$output->writeln("<error>Failed: $failedAmount</error>");
$output->writeln('done running tests');
