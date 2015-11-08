<?php
namespace PHPAssert\Core\Result;


use PHPAssert\Core\Test\ExecutionInfo;

class SingleResult implements Result
{
    private $executionInfo;

    function __construct(ExecutionInfo $executionInfo)
    {
        $this->executionInfo = $executionInfo;
    }

    function isSuccess(): \bool
    {
        return $this->executionInfo->getError() === null;
    }

    function toArray(): array
    {
        return [$this];
    }

    function getInfo(): ExecutionInfo
    {
        return $this->executionInfo;
    }
}
