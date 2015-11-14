<?php
namespace PHPAssert\Core\Test;


use PHPAssert\Core\Error\SkipException;
use PHPAssert\Core\Result\ExceptionResult;
use PHPAssert\Core\Result\Result;
use PHPAssert\Core\Result\SkipResult;

class FunctionTest implements Test
{
    private $function;

    function __construct(callable $function)
    {
        $this->function = $function;
    }

    function execute(): array
    {
        return [$this->tryExecute(microtime(true), $this->getFunctionName())];
    }

    private function tryExecute($start, $name)
    {
        try {
            call_user_func($this->function);
            return new Result($name, $this->getExecutionTime($start));
        } catch (\Error $e) {
            return new Result($name, $this->getExecutionTime($start), $e);
        } catch (SkipException $e) {
            return new SkipResult($name, $this->getExecutionTime($start), $e);
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
