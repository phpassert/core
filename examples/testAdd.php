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
    assert(false);
}

function add(...$numbers)
{
    return array_sum($numbers);
}
