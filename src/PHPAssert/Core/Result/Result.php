<?php
namespace PHPAssert\Core\Result;


class Result
{
    private $error;
    private $name;

    function __construct(\string $name, \AssertionError $error = null)
    {
        $this->error = $error;
        $this->name = $name;
    }

    function isSuccess(): \bool
    {
        return $this->error === null;
    }

    function toArray(): array
    {
        return [$this];
    }

    function getName(): \string
    {
        return $this->name;
    }
}
