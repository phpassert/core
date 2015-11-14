<?php
namespace PHPAssert\Core\Result;


class SkipResult extends Result
{
    function isSuccess(): \bool
    {
        return true;
    }

    function isSkipped(): \bool
    {
        return true;
    }
}
