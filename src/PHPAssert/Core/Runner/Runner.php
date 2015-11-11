<?php
namespace PHPAssert\Core\Runner;


use PHPAssert\Core\Discoverer\Discoverer;
use PHPAssert\Core\Reporter\Reporter;
use PHPAssert\Core\Test\Test;

class Runner
{
    private $discoverer;
    private $reporter;

    public function __construct(Discoverer $discoverer, Reporter $reporter)
    {
        $this->discoverer = $discoverer;
        $this->reporter = $reporter;
    }

    function run(): array
    {
        return $this->executeTests();
    }

    private function executeTests(): array
    {
        $tests = $this->discoverer->findTests();
        return $this->flatten(array_map([$this, 'executeTest'], $tests));
    }

    private function executeTest(Test $test)
    {
        $results = $test->execute();
        foreach ($results as $result) {
            $this->reporter->notify($result);
        }

        return $results;
    }

    private function flatten($results): array
    {
        if (count($results) > 0) {
            $results = call_user_func_array('array_merge', $results);
        }

        return $results;
    }
}
