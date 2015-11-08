<?php
namespace unit\PHPAssert\Core\TestCase;


use PHPAssert\Core\Test\ExecutionInfo;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    function executionInfoProvider()
    {
        return [
            [new ExecutionInfo('function', 10)]
        ];
    }

    function failedExecutionInfoProvider()
    {
        return [
            [new ExecutionInfo('function', 10, new \AssertionError('failed'))]
        ];
    }
}
