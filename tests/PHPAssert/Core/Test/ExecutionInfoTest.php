<?php
namespace unit\PHPAssert\Core\Test;


use PHPAssert\Core\Test\ExecutionInfo;

class ExecutionInfoTest extends \PHPUnit_Framework_TestCase
{
    private $name;
    private $duration;
    private $error;

    function setUp()
    {
        $this->name = 'function name';
        $this->error = new \AssertionError('something went wrong');
        $this->info = new ExecutionInfo($this->name, $this->error);
    }

    function testGetName()
    {
        $this->assertSame($this->name, $this->info->getName());
    }

    function testGetError()
    {
        $this->assertSame($this->error, $this->info->getError());

    }

    function testOptionalError()
    {
        $info = new ExecutionInfo($this->name, $this->duration);
        $this->assertNull($info->getError());
    }
}
