<?php
namespace PHPAssert\Core\Discoverer\FS;


abstract class FSRegexFilter extends \RecursiveRegexIterator
{
    protected $regex;

    function __construct(\RecursiveIterator $iterator, $regex)
    {
        $this->regex = $regex;
        parent::__construct($iterator, $regex);
    }
}
