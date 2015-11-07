<?php
namespace unit\PHPAssert\Core\Result;


use PHPAssert\Core\Result\SingleResult;

class SingleResultTest extends \PHPUnit_Framework_TestCase
{
    function testIsSuccess()
    {
        $result = new SingleResult();
        $this->assertTrue($result->isSuccess());
    }

    function testIsFailure()
    {
        $result = new SingleResult(new \Exception());
        $this->assertFalse($result->isSuccess());
    }

    function testToArray()
    {
        $result = new SingleResult();
        $this->assertEquals([$result], $result->toArray());
    }

    function testGetException()
    {
        $exception = new \Exception();
        $result = new SingleResult($exception);
        $this->assertSame($exception, $result->getException());
    }
}
