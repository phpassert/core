<?php
namespace PHPAssert\Core\Reporter;


use PHPAssert\Core\Result\Result;
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
        $output = $result->isSuccess() ? '.' : 'F';
        $this->writer->write($output);
    }

    function report(array $results)
    {
        $amountOfResults = count($results);
        if ($amountOfResults === 0)
        {
            $this->writer->writeln('<comment>No tests were executed</comment>');
        } else {
            $failures = $this->getFailed($results);
            $amountOfFailures = count($failures);
            if ($amountOfFailures > 0)
            {
                $this->reportFailures($failures);
                $this->writer->writeln("<error>FAIL ($amountOfResults tests, $amountOfFailures failures)</error>");
            } else
            {
                $this->writer->writeln("<info>OK ($amountOfResults tests)</info>");
            }
        }
    }

    private function getFailed(array $results)
    {
        $failures = array_values(array_filter($results, function (Result $result) {
            return !$result->isSuccess();
        }));
        return $failures;
    }

    private function reportFailures(array $results)
    {
        $amount = count($results);
        $this->writer->writeln('');
        $this->writer->writeln("There were $amount failures");
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
}
