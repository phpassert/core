<?php
namespace PHPAssert\Core\Reporter;


use PHPAssert\Core\Result\Result;
use PHPAssert\Core\Result\SkipResult;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleReporter implements Reporter
{
    private $writer;

    public function __construct(OutputInterface $writer)
    {
        $this->writer = $writer;
    }

    function notify(Result $result)
    {
        $this->writer->write($result->getSymbol());
    }

    function report(array $results)
    {
        $amountOfResults = count($results);
        $message = '<comment>No tests were executed</comment>';
        if ($amountOfResults > 0) {
            $failures = $this->getFailed($results);
            $skipped = $this->getSkipped($results);

            if (count($skipped) > 0) {
                $this->reportSkipped($skipped);
            }

            if (count($failures) > 0) {
                $this->reportFailures($failures);
                $message = "<error>FAIL ($amountOfResults tests, " . count($failures) . " failures " . count($skipped) . " skipped)</error>";
            } else {
                $message = "<info>OK ($amountOfResults tests ". count($skipped) ." skipped)</info>";
            }
        }

        $this->reportStats($results, $message);
    }

    private function getFailed(array $results)
    {
        return array_values(array_filter($results, function (Result $result) {
            return !$result->isSuccess();
        }));
    }

    private function getSkipped(array $results)
    {
        return array_values(array_filter($results, function (Result $result) {
            return $result->isSkipped();
        }));
    }

    private function reportFailures(array $results)
    {
        $this->writer->writeln('');
        $this->writer->writeln('There were ' . count($results) . ' failures');
        $this->writer->writeln('');
        foreach ($results as $i => $failure) {
            $this->reportFailed($failure, $i + 1);
        }
    }

    private function reportFailed(Result $failure, $index)
    {
        $error = $failure->getError();
        $this->writer->writeln("<fg=red>{$index}) {$failure->getName()}: {$error->getMessage()}</>");
        $this->writer->writeln($error->getTraceAsString());
        $this->writer->writeln('');
    }

    private function reportSkipped(array $results)
    {
        $this->writer->writeln('');
        $this->writer->writeln('There were ' . count($results) . ' skipped');
        $this->writer->writeln('');
        foreach ($results as $i => $skip) {
            $this->reportSkip($skip, $i + 1);
        }
    }

    private function reportSkip(SkipResult $result, $index)
    {
        $this->writer->writeln("<fg=yellow>$index) {$result->getName()}: {$result->getError()->getMessage()}");
        $this->writer->writeln('');
    }

    private function reportStats(array $results, \string $message)
    {
        $time = array_sum(array_map(function (Result $result) {
            return $result->getExecutionTimeInMs();
        }, $results));

        $this->writer->writeln("Time: $time ms");
        $this->writer->writeln('');
        $this->writer->writeln($message);
    }
}
