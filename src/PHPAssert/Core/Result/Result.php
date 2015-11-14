<?php
namespace PHPAssert\Core\Result;


class Result
{
    private $name;
    private $executionTime;
    private $error;

    function __construct(\string $name, \int $executionTime = 0, \Throwable $error = null)
    {
        $this->error = $error;
        $this->executionTime = $executionTime;
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

    function getExecutionTimeInMs()
    {
        return $this->executionTime;
    }

    function getError()
    {
        return $this->error;
    }

    function getSymbol(): \string
    {
        return $this->error === null ? '.' : $this->getFailingSymbol();
    }

    function isSkipped(): \bool
    {
        return false;
    }

    protected function getFailingSymbol(): \string
    {
        return 'F';
    }
}
