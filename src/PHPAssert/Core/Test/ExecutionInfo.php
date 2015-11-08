<?php
namespace PHPAssert\Core\Test;


class ExecutionInfo
{
    private $name;
    private $error;

    function __construct(\string $name, \AssertionError $error = null)
    {
        $this->name = $name;
        $this->error = $error;
    }

    function getName()
    {
        return $this->name;
    }

    function getError()
    {
        return $this->error;
    }
}
