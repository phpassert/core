<?php namespace PHPAssert\Core\Test;


use Underscore\Types\Arrays;
use Underscore\Types\Strings;

class ClassTest
{
    private $class;

    function __construct($class)
    {
        $this->class = $class;
    }

    function execute(): array
    {
        $this->tryExecute('beforeClass');

        $results = array_map(function (\ReflectionMethod $method) {
            $test = new FunctionTest([$this->class, $method->getName()]);

            $this->tryExecute('beforeMethod');

            $results = $test->execute();

            $this->tryExecute('afterMethod');
            return Arrays::first($results);
        }, $this->getMethods());
        $this->tryExecute('afterClass');

        return $results;
    }

    private function tryExecute($method)
    {
        if (method_exists($this->class, $method)) {
            call_user_func([$this->class, $method]);
        }
    }

    private function getMethods()
    {
        $reflector = new \ReflectionClass($this->class);
        $methods = $reflector->getMethods(\ReflectionMethod::IS_PUBLIC);
        return array_filter($methods, [$this, 'isTestMethod']);
    }

    private function isTestMethod(\ReflectionMethod $method)
    {
        $name = strtolower($method->getName());
        return !$method->isStatic()
        && (Strings::startsWith($name, 'test') || Strings::endsWith($name, 'test'));
    }
}
