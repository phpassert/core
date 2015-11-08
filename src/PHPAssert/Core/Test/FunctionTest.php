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
            // TODO: Fill in real info
            $info = new ExecutionInfo('', 10, $error ?? null);
            return new SingleResult($info);
        }
    }
}
