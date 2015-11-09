<?php
namespace unit\PHPAssert\Core\Test;


use PHPAssert\Core\Result\Result;
use PHPAssert\Core\Test\ClassTest;

class ClassTestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider classProvider
     */
    function testExecuteWithNMethod($class, array $results)
    {
        $reflector = new \ReflectionClass($class);
        $test = new ClassTest($reflector->getName());
        $this->assertEquals($results, $test->execute());
    }

    function classProvider()
    {
        $noMethods = new class() {};
        $oneMethod = new class() {
            function testMethod() {}
        };
        $twoMethods = new class() {
            function testMethod() {}
            function testOtherMethod() {}
        };

        return [
            [$noMethods, []],
            [$oneMethod, [new Result('testMethod')]],
            [$twoMethods, [new Result('testMethod'), new Result('testOtherMethod')]]
        ];
    }
}
