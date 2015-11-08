<?php
namespace unit\PHPAssert\Core\Test;


use PHPAssert\Core\Result\Result;
use PHPAssert\Core\Test\FunctionTest;

function testFake()
{
    return __FUNCTION__;
}

class FunctionTestTest extends \PHPUnit_Framework_TestCase
{
    function testExecuteShouldReturnResult()
    {
        $test = new FunctionTest('time');
        $this->assertInstanceOf(Result::class, $test->execute());
    }

    function testExecuteShouldHavePassingResult()
    {
        $test = new FunctionTest('time');
        $result = $test->execute();
        $this->assertTrue($result->isSuccess());
    }

    function testExecuteShouldHaveFailingResult()
    {
        $test = new FunctionTest(function () {
            assert(false);
        });

        $result = $test->execute();
        $this->assertFalse($result->isSuccess());
    }

    function testExecutionShouldCollectName()
    {
        $stubName = testFake();
        $reflector = new \ReflectionFunction($stubName);

        $test = new FunctionTest($stubName);
        $result = $test->execute();
        $info = $result->getInfo();

        $this->assertSame($reflector->getName(), $info->getName());
    }

    function testExecutionShouldCollectDuration()
    {
        $ms = 10;
        $test = new FunctionTest(function() use($ms) {
            usleep($ms * 1100);
        });

        $result = $test->execute();
        $info = $result->getInfo();

        $this->assertGreaterThan($ms, $info->getDurationInMS());
    }
}