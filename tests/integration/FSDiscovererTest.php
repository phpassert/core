<?php
namespace integration\PHPAssert\Core\Discoverer;


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
            return new $obj['class'](strtolower($obj['name']));
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
                        'name' => 'testAFunction',
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
            ]
        ];
    }
}
