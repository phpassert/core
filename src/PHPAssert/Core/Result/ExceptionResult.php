<?php
namespace PHPAssert\Core\Result;


class ExceptionResult extends Result
{
    protected function getFailingSymbol(): \string
    {
        return 'E';
    }
}
