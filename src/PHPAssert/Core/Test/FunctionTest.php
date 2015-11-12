<?php
namespace PHPAssert\Core\Test;


use PHPAssert\Core\Result\Result;

class FunctionTest implements Test
{
    private $function;

    function __construct(callable $function)
    {
        $this->function = $function;
    }

    function execute(): array
    {
        $start = microtime(true);
        $error = $this->tryExecute();
        $name = $this->getFunctionName();
        $time = (microtime(true) - $start) * 1000;
        return [new Result($name, (int)$time, $error)];
    }

    private function tryExecute()
    {
        try {
            call_user_func($this->function);
        } catch (\AssertionError $error) {
            return $error;
        }
    }

    private function getFunctionName()
    {
        $reflector = $this->getReflector();
        return $reflector->getName();
    }

    private function getReflector()
    {
        return is_array($this->function)
            ? new \ReflectionMethod($this->function[0], $this->function[1])
            : new \ReflectionFunction($this->function);
    }
}
