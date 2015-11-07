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
            $error = null;
            $function = $this->function;
            $function();
        } catch (\AssertionError $e) {
            $error = $e;
        } finally {
            return new SingleResult($error);
        }
    }
}
