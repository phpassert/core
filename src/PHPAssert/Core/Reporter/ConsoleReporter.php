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
                $this->writer->writeln('There was 1 failure');
                foreach ($failures as $i => $failure)
                {
                    $index = $i + 1;
                    $this->writer->writeln("{$index}) {$failure->getName()}");
                    $this->writer->writeln($failure->getError()->getTraceAsString());
                }

                $this->writer->writeln("FAIL ($amountOfResults tests, $amountOfFailures failures)");
            } else
            {
                $this->writer->writeln("OK ($amountOfResults tests)");
            }
        }
    }
}
