<?php namespace PHPAssert\Core\Test;


class ClassTest
{
    private $class;

    function __construct(\string $class)
    {
        $this->class = new $class;
    }

    function execute(): array
    {
        $reflector = new \ReflectionClass($this->class);
        $results = array_map(function(\ReflectionMethod $method) {
            $test = new FunctionTest([$this->class, $method->getName()]);
            return $test->execute();
        }, $reflector->getMethods());

        return isset($results[0]) ? call_user_func_array('array_merge', $results) : [];
    }
}
