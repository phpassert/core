<?php
namespace PHPAssert\Core\Test;


use PHPAssert\Core\Result\SingleResult;

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
        $reflector = new \ReflectionFunction($this->function);
        $name = $reflector->getName();
        return new SingleResult($name, $error);
    }

    private function tryExecute()
    {
        try {
            $function = $this->function;
            $function();
        } catch (\AssertionError $error) {
        } finally {
            return $error ?? null;
        }
    }
}
