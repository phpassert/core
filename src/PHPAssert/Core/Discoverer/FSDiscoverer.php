<?php
namespace PHPAssert\Core\Discoverer;


use PHPAssert\Core\Test\ClassTest;
use PHPAssert\Core\Test\FunctionTest;
use Underscore\Types\Strings;

class FSDiscoverer implements Discoverer
{
    private $root;

    function __construct(\string $root)
    {
        $this->root = $root;
    }

    function findTests(): array
    {
        $directory = new \RecursiveDirectoryIterator($this->root);
        $filter = new FilenameFilter($directory, '/\.php$/i');
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

    function getRoot()
    {
        return $this->root;
    }

    private function findTestsInFiles($files)
    {
        $tests = [];
        foreach (array_merge(get_declared_classes(), get_defined_functions()['user']) as $name) {
            try {
                $reflector = new \ReflectionFunction($name);
            } catch (\ReflectionException $e) {
                $reflector = new \ReflectionClass($name);
            } finally {
                $shortName = strtolower($reflector->getShortName());
                if (in_array($reflector->getFileName(), $files)
                    && (Strings::startsWith($shortName, 'test') || Strings::endsWith($shortName, 'test'))
                ) {
                    $tests[] = $this->convertReflectorToTest($reflector);
                }
            }
        }

        return $tests;
    }

    private function convertReflectorToTest($reflector)
    {
        $name = $reflector->getName();
        return method_exists($reflector, 'isClosure') ? new FunctionTest($name) : new ClassTest(new $name);
    }
}

abstract class FilesystemRegexFilter extends \RecursiveRegexIterator
{
    protected $regex;

    function __construct(\RecursiveIterator $iterator, $regex)
    {
        $this->regex = $regex;
        parent::__construct($iterator, $regex);
    }
}

class FilenameFilter extends FilesystemRegexFilter
{
    function accept()
    {
        $name = strtolower(pathinfo($this->getFileName())['filename']);
        return
            !$this->isFile() ||
            ((Strings::startsWith($name, 'test') || Strings::endsWith($name, 'test'))
                && preg_match($this->regex, $this->getFilename()));
    }
}
