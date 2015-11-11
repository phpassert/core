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
}
