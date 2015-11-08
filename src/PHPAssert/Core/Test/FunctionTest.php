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
        $start = microtime(true);
        try {
            $function = $this->function;
            $function();
        } catch (\AssertionError $e) {
            $error = $e;
        } finally {
            $duration = (int)((microtime(true) - $start) * 1000);
            $reflector = new \ReflectionFunction($this->function);
            $name = $reflector->getName();
            return new SingleResult(new ExecutionInfo($name, $duration, $error ?? null));
        }
    }
}
