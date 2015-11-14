<?php
namespace unit\PHPAssert\Core\Test;


use PHPAssert\Core\Result\ExceptionResult;
use PHPAssert\Core\Result\Result;
use PHPAssert\Core\Test\FunctionTest;
use PHPAssert\Core\Test\Test;

function testFake()
{
    return __FUNCTION__;
}

class FunctionTestTest extends \PHPUnit_Framework_TestCase
{
    function testImplementsTest()
    {
        $test = new FunctionTest('time');
        $this->assertInstanceOf(Test::class, $test);
    }

    function testExecuteShouldReturnArrayOfResults()
    {
        $test = new FunctionTest('time');
        $this->assertContainsOnlyInstancesOf(Result::class, $test->execute());
    }

    function testExecuteShouldHavePassingResult()
    {
        $test = new FunctionTest('time');
        $result = $test->execute()[0];
        $this->assertTrue($result->isSuccess());
    }

    function testExecuteShouldHaveFailingResult()
    {
        $test = new FunctionTest(function () {
            assert(false);
        });

        $result = $test->execute()[0];
        $this->assertFalse($result->isSuccess());
    }

    function testExecuteShouldCatchException()
    {
        $test = new FunctionTest(function() {
            throw new \Exception();
        });

        $result = $test->execute()[0];
        $this->assertInstanceOf(ExceptionResult::class, $result);
        $this->assertFalse($result->isSuccess());
    }

    function testExecutionShouldCollectName()
    {
        $stubName = testFake();
        $reflector = new \ReflectionFunction($stubName);

        $test = new FunctionTest($stubName);
        $result = $test->execute()[0];

        $this->assertSame($reflector->getName(), $result->getName());
    }

    function testExecutionShouldCollectExecutionTime()
    {
        $ms = 10;
        $test = new FunctionTest(function () use ($ms) {
            usleep($ms * 1000);
        });
        $result = $test->execute()[0];
        $this->assertGreaterThanOrEqual($ms, $result->getExecutionTimeInMs());
    }
}
