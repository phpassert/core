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
            $this->writer->writeln('No tests were executed');
        } else {
            $failures = array_values(array_filter($results, function(Result $result) {
                return !$result->isSuccess();
            }));

            $amountOfFailures = count($failures);
            if ($amountOfFailures > 0)
            {
                $this->writer->writeln('');
                $this->writer->writeln("There were $amountOfFailures failures");
                foreach ($failures as $i => $failure)
                {
                    $error = $failure->getError();
                    $index = $i + 1;
                    $this->writer->writeln("{$index}) {$failure->getName()}: {$error->getMessage()}");
                    $this->writer->writeln($error->getTraceAsString());
                    $this->writer->writeln('');
                }

                $this->writer->writeln("FAIL ($amountOfResults tests, $amountOfFailures failures)");
            } else
            {
                $this->writer->writeln("OK ($amountOfResults tests)");
            }
        }
    }
}
