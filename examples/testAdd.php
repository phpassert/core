<?php
namespace PHPAssert\examples;

function testAdd()
{
    assert(add(1, 2) === 3);
}

function testAddVariadic()
{
    $numbers = range(0, 4);
    $sum = call_user_func_array(add::class, $numbers);
    assert($sum === array_sum($numbers));
}

function testFail()
{
    assert(1 == 2);
}

function testFailWithCustomMessage()
{
    assert(2 === 3, new \AssertionError('2 is not equal to 3'));
}

function add(...$numbers)
{
    return array_sum($numbers);
}
