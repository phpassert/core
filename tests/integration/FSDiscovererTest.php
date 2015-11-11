<?php
namespace integration\PHPAssert\Core\Discoverer;


use org\bovigo\vfs\vfsStream;
use PHPAssert\Core\Discoverer\Discoverer;
use PHPAssert\Core\Discoverer\FSDiscoverer;
use PHPAssert\Core\Test\ClassTest;
use PHPAssert\Core\Test\FunctionTest;

class FilesystemDiscovererTest extends \PHPUnit_Framework_TestCase
{
    function testInstanceOfDiscoverer()
    {
        $discoverer = new FSDiscoverer(__DIR__);
        $this->assertInstanceOf(Discoverer::class, $discoverer);
    }

    function testRoot()
    {
        $root = __DIR__;
        $discoverer = new FSDiscoverer($root);
        $this->assertSame($root, $discoverer->getRoot());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @dataProvider testsFileStructureProvider
     */
    function testTestsFound(array $fileStructure, array $testObjects)
    {
        $fs = vfsStream::create($fileStructure, vfsStream::setup());
        $discoverer = new FSDiscoverer($fs->url());
        $tests = $discoverer->findTests();
        $this->assertEquals(array_map(function ($obj) {
            if (isset($obj['name'])) {
                return new $obj['class']($obj['name']);
            } else {
                return new $obj['class'](new $obj['instance']);
            }
        }, $testObjects), $tests);
    }

    function testsFileStructureProvider()
    {
        return [
            [[], []],
            [
                [
                    'testIgnore.txt' => '<?php function testIgnore() {}',
                    'ignore.php' => '<?php function testIgnore() {}',
                ],
                []
            ],
            [
                [
                    'testFileOne.php' => '<?php function TESTAFunction() {}',
                    'testFileTwo.php' => '<?php function testAFunctionTwo() {} function testAFunctionThree() {}'
                ],
                [
                    [
                        'name' => 'TESTAFunction',
                        'class' => FunctionTest::class
                    ],
                    [
                        'name' => 'testAFunctionTwo',
                        'class' => FunctionTest::class
                    ],
                    [
                        'name' => 'testAFunctionThree',
                        'class' => FunctionTest::class
                    ]
                ],
            ],
            [
                [
                    'fileOneTest.php' => '<?php function testAFunction() {}'
                ],
                [
                    [
                        'name' => 'testAFunction',
                        'class' => FunctionTest::class
                    ]
                ]
            ],
            [
                [
                    'testOne.php' => '<?php function testSomeFunction() {}',
                    'recursion' =>
                        [
                            'testTwo.php' => '<?php function testAnotherFunction() {}',
                            'recursion' =>
                                [
                                    'threeTest.php' => '<?php function testThirdLevelRecursion() {}'
                                ]
                        ]
                ],
                [
                    [
                        'name' => 'testSomeFunction',
                        'class' => FunctionTest::class
                    ],
                    [
                        'name' => 'testAnotherFunction',
                        'class' => FunctionTest::class
                    ],
                    [
                        'name' => 'testThirdLevelRecursion',
                        'class' => FunctionTest::class
                    ]
                ]
            ],
            [
                [
                    'testClass.php' => '<?php class ignoremeclass {} ?>'
                ],
                []
            ],
            [
                [
                    'testClass.php' => '<?php class testSomething {}'
                ],
                [
                    [
                        'instance' => 'testSomething',
                        'class' => ClassTest::class
                    ]
                ]
            ],
            [
                [
                    'testOne.php' => '<?php namespace PHPAssert\\test; function testF() {}',
                    'testTwo.php' => '<?php namespace PHPAssert\\test; class TestA {}'
                ],
                [
                    [
                        'instance' => 'PHPAssert\test\TestA',
                        'class' => ClassTest::class
                    ],
                    [
                        'name' => 'PHPAssert\test\testF',
                        'class' => FunctionTest::class
                    ]
                ]
            ]
        ];
    }
}
