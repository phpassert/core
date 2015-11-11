<?php

require_once('bootstrap.php');

$discoverer = new PHPAssert\Core\Discoverer\FSDiscoverer(__DIR__ . DIRECTORY_SEPARATOR . 'examples');
$tests = $discoverer->findTests();
//TODO: Implement runner
echo 'start executing tests' . PHP_EOL;
$results = array_map(function(\PHPAssert\Core\Test\Test $test) {
    return $test->execute();
}, $tests);

$results = call_user_func_array('array_merge', $results);
foreach ($results as $result) {
    if (!$result->isSuccess()) {
        echo "{$result->getName()} failed" . PHP_EOL;
    }
}

echo 'done running tests' . PHP_EOL;
