<?php
namespace PHPAssert\Core\Test;


use PHPAssert\Core\Result\Result;

class FunctionTest
{
    private $function;

    function __construct(callable $function)
    {
        $this->function = $function;
    }

    function execute()
    {
        $error = $this->tryExecute();
        $name = $this->getFunctionName();
        return [new Result($name, $error)];
    }

    private function tryExecute()
    {
        try {
            $this->getReflector()->invoke();
        } catch (\AssertionError $error) {
        } finally {
            return $error ?? null;
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
