<?php
namespace PHPAssert\Core\Test;


use PHPAssert\Core\Result\ExceptionResult;
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
        return [$this->tryExecute()];
    }

    private function tryExecute()
    {
        $start = microtime(true);
        $name = $this->getFunctionName();
        try {
            call_user_func($this->function);
            return new Result($name, $this->getExecutionTime($start));
        } catch (\Error $e) {
            return new Result($name, $this->getExecutionTime($start), $e);
        } catch (\Exception $e) {
            return new ExceptionResult($name, $this->getExecutionTime($start), $e);
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

    private function getExecutionTime($start)
    {
        return (microtime(true) - $start) * 1000;
    }
}
