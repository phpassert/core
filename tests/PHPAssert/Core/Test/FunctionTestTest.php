<?php
namespace unit\PHPAssert\Core\Test;


use PHPAssert\Core\Result\Result;
use PHPAssert\Core\Test\FunctionTest;

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
}
