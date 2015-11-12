<?php
namespace unit\PHPAssert\Core\Reporter;


use PHPAssert\Core\Reporter\ConsoleReporter;
use PHPAssert\Core\Reporter\Reporter;
use PHPAssert\Core\Result\Result;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleReporterTest extends \PHPUnit_Framework_TestCase
{
    function testInstanceOfReporter()
    {
        $streamWriter = $this->getMock(OutputInterface::class);
        $reporter = new ConsoleReporter($streamWriter);
        $this->assertInstanceOf(Reporter::class, $reporter);
    }

    /**
     * @dataProvider resultProvider
     */
    function testNotify(Result $result, $output)
    {
        $streamWriter = $this->getMock(OutputInterface::class);
        $streamWriter->expects($this->once())
            ->method('write')
            ->with($output);

        $reporter = new ConsoleReporter($streamWriter);
        $reporter->notify($result);
    }

    /**
     * @dataProvider reportProvider
     */
    function testReport(array $results, array $output)
    {
        $streamWriter = $this->getMock(OutputInterface::class);
        $method = $streamWriter->expects($this->any())
            ->method('writeln');

        call_user_func_array([$method, 'withConsecutive'], array_map(function($line) {
            return [$this->equalTo($line)];
        }, $output));

        $reporter = new ConsoleReporter($streamWriter);
        $reporter->report($results);
    }

    function resultProvider()
    {
        return [
            [new Result('success'), '.'],
            [new Result('fail', new \AssertionError()), 'F'],
        ];
    }

    function reportProvider()
    {
        $success = new Result('');
        $fail = new Result('TestMethod', new \AssertionError('failed'));
        $trace = $fail->getError()->getTraceAsString();
        $message = $fail->getError()->getMessage();
        return [
            [[], ['<comment>No tests were executed</comment>']],
            [[$success], ['<info>OK (1 tests)</info>']],
            [[$success, $fail], [
                '',
                'There were 1 failures',
                '',
                "<fg=red>1) {$fail->getName()}: $message</>",
                $trace,
                '',
                '<error>FAIL (2 tests, 1 failures)</error>'
            ]],
        ];
    }
}
