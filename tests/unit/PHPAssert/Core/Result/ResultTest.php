<?php
namespace unit\PHPAssert\Core\Result;


use PHPAssert\Core\Result\Result;
use unit\PHPAssert\Core\TestCase\TestCase;

class ResultTest extends TestCase
{
    function testIsSuccess()
    {
        $result = new Result('f');
        $this->assertTrue($result->isSuccess());
    }

    function testIsFailure()
    {
        $result = new Result('f', new \AssertionError());
        $this->assertFalse($result->isSuccess());
    }

    function testToArray()
    {
        $result = new Result('f');
        $this->assertEquals([$result], $result->toArray());
    }

    function testGetName()
    {
        $name = 'f';
        $result = new Result($name);
        $this->assertSame($name, $result->getName());
    }
}
