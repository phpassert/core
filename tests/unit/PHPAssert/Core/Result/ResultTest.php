<?php
namespace unit\PHPAssert\Core\Result;


use PHPAssert\Core\Result\Result;

class ResultTest extends \PHPUnit_Framework_TestCase
{
    function testIsSuccess()
    {
        $result = new Result('f');
        $this->assertTrue($result->isSuccess());
    }

    function testIsFailure()
    {
        $result = new Result('f', 0, new \AssertionError());
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

    function testGetExecutionTime()
    {
        $time = 0;
        $result = new Result('f', $time);
        $this->assertSame($time, $result->getExecutionTimeInMs());

    }

    function testGetError()
    {
        $error = new \AssertionError();
        $result = new Result('', 0, $error);
        $this->assertSame($error, $result->getError());
    }

    /**
     * @dataProvider resultProvider
     */
    function testGetSymbol(Result $result, \string $expected)
    {
        $this->assertSame($expected, $result->getSymbol());
    }

    function resultProvider()
    {
        return [
            [new Result('', 0), '.'],
            [new Result('', 0, new \AssertionError()), 'F']
        ];
    }
}
