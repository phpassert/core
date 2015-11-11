<?php
namespace PHPAssert\Core\Discoverer;


use PHPAssert\Core\Discoverer\FS\TestFilter;
use PHPAssert\Core\Test\ClassTest;
use PHPAssert\Core\Test\FunctionTest;
use function PHPAssert\Core\Utils\Test\isValidTestName;

class FSDiscoverer implements Discoverer
{
    private $root;

    function __construct(\string $root)
    {
        $this->root = $root;
    }

    function getRoot()
    {
        return $this->root;
    }

    function findTests(): array
    {
        $directory = new \RecursiveDirectoryIterator($this->root);
        $filter = new TestFilter($directory, '/\.php$/i');
        $fileNames = [];
        foreach (new \RecursiveIteratorIterator($filter) as $file) {
            if ($file->isFile()) {
                $path = $file->getPathName();
                $fileNames[] = $path;
                require_once($path);
            }
        }

        return $this->findTestsInFiles($fileNames);
    }

    private function findTestsInFiles($files)
    {
        $tests = [];
        foreach ($this->getDeclaredObjects() as $object) {
            $reflector = $this->getReflector($object);
            if ($this->isValidTestFile($reflector, $files)) {
                $tests[] = $this->convertReflectorToTest($reflector);
            }
        }

        return $tests;
    }

    private function getDeclaredObjects()
    {
        return array_merge(get_declared_classes(), get_defined_functions()['user']);
    }

    private function getReflector(\string $name)
    {
        try {
            return new \ReflectionFunction($name);
        } catch (\ReflectionException $e) {
            return new \ReflectionClass($name);
        }
    }

    private function isValidTestFile(\Reflector $reflector, array $testFiles)
    {
        return in_array($reflector->getFileName(), $testFiles)
        && isValidTestName($reflector->getShortName());
    }

    private function convertReflectorToTest(\Reflector $reflector)
    {
        $name = $reflector->getName();
        return method_exists($reflector, 'isClosure')
            ? new FunctionTest($name)
            : new ClassTest(new $name);
    }
}
