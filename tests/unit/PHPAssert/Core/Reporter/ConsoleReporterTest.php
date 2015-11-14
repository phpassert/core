<?php
namespace unit\PHPAssert\Core\Reporter;


use PHPAssert\Core\Reporter\ConsoleReporter;
use PHPAssert\Core\Reporter\Reporter;
use PHPAssert\Core\Result\ExceptionResult;
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
            [new Result('fail', 10, new \AssertionError()), 'F'],
            [new ExceptionResult('error', 10, new \Exception()), 'E']
        ];
    }

    function reportProvider()
    {
        $success = new Result('', 10);
        $fail = new Result('TestMethod', 10, new \AssertionError('failed'));
        $exception = new Result('exception', 10, new \Exception('exception throw'));

        $trace = $fail->getError()->getTraceAsString();
        $message = $fail->getError()->getMessage();

        $exceptionMessage = $exception->getError()->getMessage();
        return [
            [[], [
                'Time: 0 ms',
                '',
                '<comment>No tests were executed</comment>'
            ]],
            [[$success], [
                'Time: 10 ms',
                '',
                '<info>OK (1 tests)</info>'
            ]],
            [[$success, $fail, $exception], [
                '',
                'There were 2 failures',
                '',
                "<fg=red>1) {$fail->getName()}: $message</>",
                $trace,
                '',
                "<fg=red>2) {$exception->getName()}: $exceptionMessage</>",
                $exception->getError()->getTraceAsString(),
                '',
                'Time: 30 ms',
                '',
                '<error>FAIL (3 tests, 2 failures)</error>'
            ]],
        ];
    }
}
