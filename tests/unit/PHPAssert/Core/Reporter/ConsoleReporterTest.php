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
    function testNotifySuccess(Result $result, $output)
    {
        $streamWriter = $this->getMock(OutputInterface::class);
        $streamWriter->expects($this->once())
            ->method('write')
            ->with($output);

        $reporter = new ConsoleReporter($streamWriter);
        $reporter->notify($result);
    }

    function resultProvider()
    {
        return [
            [new Result('success'), '.'],
            [new Result('fail', new \AssertionError()), 'F'],
        ];
    }
}
