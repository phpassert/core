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

    function toArray(): array
    {
        return [$this];
    }

    function execute()
    {
        $error = $this->tryExecute();
        $reflector = new \ReflectionFunction($this->function);
        $name = $reflector->getName();
        return new Result($name, $error);
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
