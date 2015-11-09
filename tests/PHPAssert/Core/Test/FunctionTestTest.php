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

    function testExecutionShouldCollectName()
    {
        $stubName = testFake();
        $reflector = new \ReflectionFunction($stubName);

        $test = new FunctionTest($stubName);
        $result = $test->execute()[0];

        $this->assertSame($reflector->getName(), $result->getName());
    }
}
