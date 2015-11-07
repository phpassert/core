<?php
namespace PHPAssert\Core\Result;


interface Result
{
    function isSuccess(): \bool;
    function toArray(): array;
    function getError();
}
