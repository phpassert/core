<?php
namespace PHPAssert\Core\Result;


class SingleResult implements Result
{
    private $exception;

    function __construct(\Exception $exception = null)
    {
        $this->exception = $exception;
    }

    function isSuccess(): bool
    {
        return $this->exception === null;
    }

    function toArray(): array
    {
        return [$this];
    }

    function getException()
    {
        return $this->exception;
    }
}
