<?php
namespace unit\PHPAssert\Core\Discoverer;


use org\bovigo\vfs\vfsStream;
use PHPAssert\Core\Discoverer\FSDiscoverer;
use PHPAssert\Core\Test\FunctionTest;

class FilesystemDiscovererTest extends \PHPUnit_Framework_TestCase
{
    function testRoot()
    {
        $root = __DIR__;
        $discoverer = new FSDiscoverer($root);
        $this->assertSame($root, $discoverer->getRoot());
    }

    function testNoTestsFound()
    {
        $root = vfsStream::setup();
        $discoverer = new FSDiscoverer($root->url());
        $this->assertEquals([], $discoverer->findTests());
    }

    function testTestFound()
    {
        $fixtureDir = ROOT_DIR
            . DIRECTORY_SEPARATOR
            . 'tests'
            . DIRECTORY_SEPARATOR
            . 'fixtures'
            . DIRECTORY_SEPARATOR . 'tests';

        $discoverer = new FSDiscoverer($fixtureDir);
        $tests = $discoverer->findTests();
        $this->assertEquals([new FunctionTest('testsum'), new FunctionTest('testadd')], $tests);
    }
}
