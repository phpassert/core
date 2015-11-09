<?php namespace PHPAssert\Core\Test;


use Underscore\Types\Strings;

class ClassTest
{
    private $class;

    function __construct(\string $class)
    {
        $this->class = new $class;
    }

    function execute(): array
    {
        $methods = $this->getMethods();
        $results = array_map(function (\ReflectionMethod $method) {
            $test = new FunctionTest([$this->class, $method->getName()]);
            return $test->execute();
        }, $methods);

        return isset($results[0]) ? call_user_func_array('array_merge', $results) : [];
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
