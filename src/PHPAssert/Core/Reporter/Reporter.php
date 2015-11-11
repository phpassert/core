<?php
namespace PHPAssert\Core\Reporter;


use PHPAssert\Core\Result\Result;

interface Reporter
{
    function notify(Result $result);
    function report(array $results);
}
