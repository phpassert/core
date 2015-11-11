<?php
namespace PHPAssert\Core\Utils\Test;

use Underscore\Types\Strings;

function isValidTestName(\string $name)
{
    $name = strtolower($name);
    return Strings::startsWith($name, 'test') || Strings::endsWith($name, 'test');
}
