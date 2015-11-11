<?php
namespace PHPAssert\Examples;


class TestCalculator
{
    /**
     * @var Calculator
     */
    private $calc;

    function beforeMethod()
    {
        $this->calc = new Calculator();
    }

    function testAddWithNoArg()
    {
        assert($this->calc->add() === 0);
    }

    function testAddWithOneArg()
    {
        assert($this->calc->add(1) === 1);
    }

    function testAddWithMultipleArgs()
    {
        assert($this->calc->add(1, 2) === 3);
    }
}

class Calculator
{
    function add(...$numbers)
    {
        return array_sum($numbers);
    }
}
