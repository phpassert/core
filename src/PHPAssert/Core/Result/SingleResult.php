<?php
namespace PHPAssert\Core\Result;


class SingleResult
{
    private $exception;

    function __construct(\Exception $exception = null)
    {
        $this->exception = $exception;
    }

    function isSuccess()
    {
        return $this->exception === null;
    }

    function toArray()
    {
        return [$this];
    }

    function getException()
    {
        return $this->exception;
    }
}
