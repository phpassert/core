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
        try {
            $function = $this->function;
            $function();
        } catch (\AssertionError $e) {
            $error = $e;
        } finally {
            $reflector = new \ReflectionFunction($this->function);
            $name = $reflector->getName();
            return new SingleResult(new ExecutionInfo($name, $error ?? null));
        }
    }
}
