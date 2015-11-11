<?php
namespace PHPAssert\Core\Discoverer;


use PHPAssert\Core\Test\FunctionTest;
use Underscore\Types\Arrays;
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
        $filter = new FilenameFilter($directory, '/\.php$/i');
        $fileNames = [];
        foreach (new \RecursiveIteratorIterator($filter) as $file) {
            if ($file->isFile()) {
                $path = $file->getPathName();
                $fileNames[] = $path;
                require_once($path);
            }
        }

        $functions = get_defined_functions()['user'];
        $functions = array_filter($functions, function(\string $name) use($fileNames) {
            $reflector = new \ReflectionFunction($name);
            return in_array($reflector->getFileName(), $fileNames)
                && (Strings::startsWith($name, 'test') || Strings::endsWith($name, 'test'));
        });

        $tests = array_map(function($name) {
            return new FunctionTest($name);
        }, $functions);

        return array_merge([], $tests);
    }

    function getRoot()
    {
        return $this->root;
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
