<?php
namespace unit\PHPAssert\Core\Test;


use PHPAssert\Core\Result\Result;
use PHPAssert\Core\Test\ClassTest;
use PHPAssert\Core\Test\Test;

class ClassTestTest extends \PHPUnit_Framework_TestCase
{
    function testImplementsTest()
    {
        $test = new ClassTest(new class() {});
        $this->assertInstanceOf(Test::class, $test);
    }

    /**
     * @dataProvider classProvider
     */
    function testExecuteWithNMethod($class, array $results)
    {
        $test = new ClassTest($class);
        $this->assertEquals($results, $test->execute());
    }

    /**
     * @dataProvider classFlowProvider
     */
    function testExecutionFlow($mock)
    {
        $test = new ClassTest($mock);
        $test->execute();
    }

    function classFlowProvider()
    {

        return [
            [$this->getMockFakeTestCase(1, 'beforeClass')],
            [$this->getMockFakeTestCase(2, 'beforeMethod')],
            [$this->getMockFakeTestCase(2, 'afterMethod')],
            [$this->getMockFakeTestCase(1, 'afterClass')]
        ];
    }

    function getMockFakeTestCase(\int $calls, \string $method)
    {
        $mock = $this->getMock(FakeTestCase::class);
        $mock->expects($this->exactly($calls))->method($method);

        return $mock;
    }

    function classProvider()
    {
        $noMethods = new class()
        {
        };
        $oneMethod = new class()
        {
            function testMethod()
            {
            }
        };
        $twoMethods = new class()
        {
            function testMethod()
            {
            }

            function otherMethodTest()
            {
            }

            function notATestMethod()
            {

            }
        };

        $ignoreMethods = new class()
        {
            private function testSomething()
            {

            }

            static function testDoSomething()
            {

            }

            function notATestMethod()
            {

            }
        };

        return [
            [$noMethods, []],
            [$oneMethod, [new Result('testMethod')]],
            [$twoMethods, [new Result('testMethod'), new Result('otherMethodTest')]],
            [$ignoreMethods, []]
        ];
    }
}

class FakeTestCase
{
    function beforeClass()
    {

    }

    function beforeMethod()
    {

    }

    function testMethod()
    {

    }

    function testMethod2()
    {

    }

    function afterMethod()
    {

    }

    function afterClass()
    {

    }
}