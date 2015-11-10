<?php
namespace PHPAssert\Core\Discoverer;


use PHPAssert\Core\Test\FunctionTest;
use Underscore\Types\Strings;

class FSDiscoverer
{
    private $root;

    function __construct(\string $root)
    {
        $this->root = $root;
    }

    function findTests()
    {
        $directory = new \RecursiveDirectoryIterator($this->root);
        $iterator = new \RecursiveIteratorIterator($directory);
        $fileNames = array_map(function(\SplFileInfo $file) {
            $path = $file->getPathName();
            require_once($path);
            return $path;
        }, iterator_to_array(new \RegexIterator($iterator, '/\.php$/i', \RegexIterator::MATCH)));

        $functions = array_filter(get_defined_functions()['user'], function (\string $name) use ($fileNames) {
            $reflector = new \ReflectionFunction($name);
            return in_array($reflector->getFileName(), $fileNames)
            && (Strings::startsWith($name, 'test') || Strings::endsWith($name, 'test'));
        });

        $tests = array_map(function (\string $name) {
            return new FunctionTest($name);
        }, $functions);

        return array_merge([], $tests);
    }

    function getRoot()
    {
        return $this->root;
    }
}