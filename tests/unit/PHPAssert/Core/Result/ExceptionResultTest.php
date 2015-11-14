<?php
namespace unit\PHPAssert\Core\Result;


use PHPAssert\Core\Result\ExceptionResult;
use PHPAssert\Core\Result\Result;

class ExceptionResultTest extends \PHPUnit_Framework_TestCase
{
    function testInstanceOfResult()
    {
        $result = new ExceptionResult('result', 0);
        $this->assertInstanceOf(Result::class, $result);
    }

    /**
     * @dataProvider resultProvider
     */
    function testIsSkipped(ExceptionResult $result)
    {
        $this->assertFalse($result->isSkipped());
    }

    /**
     * @dataProvider resultProvider
     */
    function testGetSymbol(ExceptionResult $result, \string $symbol)
    {
        $this->assertSame($symbol, $result->getSymbol());
    }

    function resultProvider()
    {
        return [
            [new ExceptionResult('result', 0), '.'],
            [new ExceptionResult('result', 0, new \Exception()), 'E']
        ];
    }
}
