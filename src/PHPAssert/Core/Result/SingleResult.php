<?php
namespace PHPAssert\Core\Result;


class SingleResult implements Result
{
    private $error;

    function __construct(\AssertionError $error = null)
    {
        $this->error = $error;
    }

    function isSuccess(): \bool
    {
        return $this->error === null;
    }

    function toArray(): array
    {
        return [$this];
    }

    function getError()
    {
        return $this->error;
    }
}
