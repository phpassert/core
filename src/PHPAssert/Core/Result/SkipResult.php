<?php
namespace PHPAssert\Core\Result;


class SkipResult extends Result
{
    function isSuccess(): \bool
    {
        return true;
    }

    function getSymbol(): \string
    {
        return 'S';
    }

    function isSkipped(): \bool
    {
        return true;
    }
}
