<?php
namespace unit\PHPAssert\Core\Result;


use PHPAssert\Core\Result\Result;
use PHPAssert\Core\Result\SingleResult;
use unit\PHPAssert\Core\TestCase\TestCase;

class SingleResultTest extends TestCase
{
    function testImplementsResult()
    {
        $this->assertInstanceOf(Result::class, new SingleResult('f'));
    }

    function testIsSuccess()
    {
        $result = new SingleResult('f');
        $this->assertTrue($result->isSuccess());
    }

    function testIsFailure()
    {
        $result = new SingleResult('f', new \AssertionError());
        $this->assertFalse($result->isSuccess());
    }

    function testToArray()
    {
        $result = new SingleResult('f');
        $this->assertEquals([$result], $result->toArray());
    }

    function testGetName()
    {
        $name = 'f';
        $result = new SingleResult($name);
        $this->assertSame($name, $result->getName());
    }
}
