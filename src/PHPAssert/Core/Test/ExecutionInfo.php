<?php
namespace PHPAssert\Core\Test;


class ExecutionInfo
{
    private $name;
    private $duration;
    private $error;

    function __construct(\string $name, \int $ms, \AssertionError $error = null)
    {
        $this->name = $name;
        $this->duration = $ms;
        $this->error = $error;
    }

    function getName()
    {
        return $this->name;
    }

    function getDurationInMS()
    {
        return $this->duration;
    }

    function getError()
    {
        return $this->error;
    }
}
