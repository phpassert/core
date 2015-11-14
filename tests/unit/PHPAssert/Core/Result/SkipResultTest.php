<?php
namespace unit\PHPAssert\Core\Result;

use PHPAssert\Core\Error\SkipException;
use PHPAssert\Core\Result\Result;
use PHPAssert\Core\Result\SkipResult;

class SkipResultTest extends \PHPUnit_Framework_TestCase
{
    function testInstanceOfResult()
    {
        $result = new SkipResult('', 0);
        $this->assertInstanceOf(Result::class, $result);
    }

    /**
     * @dataProvider resultProvider
     */
    function testGetSymbol(SkipResult $result)
    {
        $this->assertSame('S', $result->getSymbol());
    }

    /**
     * @dataProvider resultProvider
     */
    function testIsSuccess(SkipResult $result)
    {
        $this->assertTrue($result->isSuccess());
    }

    /**
     * @dataProvider resultProvider
     */
    function testIsSkipped(SkipResult $result)
    {
        $this->assertTrue($result->isSkipped());
    }

    function resultProvider()
    {
        return [
            [new SkipResult('', 0)],
            [new SkipResult('', 0, new SkipException())]
        ];
    }
}
