<?php

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} else {
    require __DIR__ . '/../../vendor/autoload.php';
}

$factory = new Clue\React\SQLite\Factory();
$db = $factory->openLazy(':memory:', null, ['idle' => 0.001]);

$query = isset($argv[1]) ? $argv[1] : 'SELECT 42 AS value';
$args = array_slice(isset($argv) ? $argv : [], 2);

$db->query('SELECT ? AS answer', [42])->then(function (Clue\React\SQLite\Result $result) {
    if ($result->columns !== ['answer'] || count($result->rows) !== 1 || $result->rows[0]['answer'] !== 42) {
        var_dump($result);
        throw new RuntimeException('Unexpected result');
    }

    $answer = $result->rows[0]['answer'];
    echo 'Answer: ' . $answer . PHP_EOL;
})->then(null, function (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    exit(1);
});
