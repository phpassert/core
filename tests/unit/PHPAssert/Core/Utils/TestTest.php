<?php
namespace unit\PHPAssert\Core\Utils;

class TestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider testNameProvider
     */
    function testIsValidTestName(\string $name, \bool $expected)
    {
        $this->assertSame($expected, \PHPAssert\Core\Utils\Test\isValidTestName($name));
    }

    function testNameProvider()
    {
        return [
            ['', false],
            ['testA', true],
            ['TESTA', true],
            ['aTest', true],
            ['aTEST', true],
            ['testAtest', true]
        ];
    }
}
