<?php
namespace PHPAssert\Core\Discoverer\FS;

use function PHPAssert\Core\Utils\Test\isValidTestName;

class TestFilter extends FSRegexFilter
{
    function accept()
    {
        $name = pathinfo($this->getFileName())['filename'];
        return !$this->isFile() || (isValidTestName($name) && preg_match($this->regex, $this->getFilename()));
    }
}
