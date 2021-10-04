<?php

require __DIR__ . '/../vendor/autoload.php';

$factory = new Clue\React\SQLite\Factory();

$search = isset($argv[1]) ? $argv[1] : 'foo';
$db = $factory->openLazy('test.db');

$db->query('SELECT * FROM foo WHERE bar LIKE ?', ['%' . $search . '%'])->then(function (Clue\React\SQLite\Result $result) {
    echo 'Found ' . count($result->rows) . ' rows: ' . PHP_EOL;
    echo implode("\t", $result->columns) . PHP_EOL;
    foreach ($result->rows as $row) {
        echo implode("\t", $row) . PHP_EOL;
    }
}, function (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
});

$db->quit();
