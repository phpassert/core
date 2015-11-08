<?php
namespace unit\PHPAssert\Core\TestCase;


use PHPAssert\Core\Test\ExecutionInfo;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    function executionInfoProvider()
    {
        return [
            [new ExecutionInfo('function')]
        ];
    }

    function failedExecutionInfoProvider()
    {
        return [
            [new ExecutionInfo('function', new \AssertionError('failed'))]
        ];
    }
}
