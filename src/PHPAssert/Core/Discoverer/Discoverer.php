<?php
namespace PHPAssert\Core\Discoverer;


interface Discoverer
{
    function findTests(): array;
}