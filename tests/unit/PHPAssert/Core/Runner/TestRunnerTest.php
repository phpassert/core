<?php
namespace unit\PHPAssert\Core\Runner;


use PHPAssert\Core\Discoverer\Discoverer;
use PHPAssert\Core\Reporter\Reporter;
use PHPAssert\Core\Result\Result;
use PHPAssert\Core\Runner\Runner;
use PHPAssert\Core\Test\ClassTest;
use PHPAssert\Core\Test\FunctionTest;
use PHPAssert\Core\Test\Test;

class TestRunnerTest extends \PHPUnit_Framework_TestCase
{
    function testShouldExecuteTests()
    {
        $reporter = $this->getMock(Reporter::class);
        $results = [new Result('function')];
        $test = $this->getMock(Test::class);
        $test->expects($this->once())
            ->method('execute')
            ->willReturn($results);

        $discoverer = $this->getMock(Discoverer::class);
        $discoverer->expects($this->once())->method('findTests')->willReturn([$test]);
        $runner = new Runner($discoverer, $reporter);
        $this->assertEquals($results, $runner->run());
    }

    function testNoTests()
    {
        $reporter = $this->getMock(Reporter::class);
        $discoverer = $this->getMock(Discoverer::class);
        $discoverer->expects($this->once())
            ->method('findTests')
            ->willReturn([]);
        $runner = new Runner($discoverer, $reporter);
        $runner->run();
    }

    /**
     * @dataProvider logTestProvider
     */
    function testLoggingResult(Test $test, \int $notifyCount)
    {
        $discoverer = $this->getMock(Discoverer::class);
        $discoverer->expects($this->once())
            ->method('findTests')
            ->willReturn([$test]);
        $reporter = $this->getMock(Reporter::class);
        $reporter->expects($this->exactly($notifyCount))
            ->method('notify');

        $runner = new Runner($discoverer, $reporter);
        $runner->run();
    }

    function logTestProvider()
    {
        $class = new class()
        {
            function testMethod()
            {
            }

            function testMethod2()
            {
            }

            function testMethod3()
            {
            }
        };

        return [
            [new FunctionTest('time'), 1],
            [new ClassTest($class), 3]
        ];
    }
}
