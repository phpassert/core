<?php
namespace PHPAssert\Core\Result;


use PHPAssert\Core\Test\ExecutionInfo;

interface Result
{
    function isSuccess(): \bool;
    function toArray(): array;
    function getInfo(): ExecutionInfo;
}
