<?php
namespace unit\PHPAssert\Core\Result;


use PHPAssert\Core\Result\Result;
use PHPAssert\Core\Result\SingleResult;
use PHPAssert\Core\Test\ExecutionInfo;
use unit\PHPAssert\Core\TestCase\TestCase;

class SingleResultTest extends TestCase
{
    /**
     * @dataProvider executionInfoProvider
     */
    function testImplementsResult(ExecutionInfo $info)
    {
        $this->assertInstanceOf(Result::class, new SingleResult($info));
    }

    /**
     * @dataProvider executionInfoProvider
     */
    function testIsSuccess(ExecutionInfo $info)
    {
        $result = new SingleResult($info);
        $this->assertTrue($result->isSuccess());
    }

    /**
     * @dataProvider failedExecutionInfoProvider
     */
    function testIsFailure(ExecutionInfo $info)
    {
        $result = new SingleResult($info);
        $this->assertFalse($result->isSuccess());
    }

    /**
     * @dataProvider executionInfoProvider
     */
    function testToArray(ExecutionInfo $info)
    {
        $result = new SingleResult($info);
        $this->assertEquals([$result], $result->toArray());
    }

    /**
     * @dataProvider executionInfoProvider
     */
    function testGetInfo(ExecutionInfo $info)
    {
        $result = new SingleResult($info);
        $this->assertSame($info, $result->getInfo());
    }
}
