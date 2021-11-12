<?php

require __DIR__ . '/../vendor/autoload.php';

$factory = new Clue\React\SQLite\Factory();
$db = $factory->openLazy(__DIR__ . '/users.db');

$search = isset($argv[1]) ? $argv[1] : '';

$db->query('SELECT * FROM user WHERE name LIKE ?', ['%' . $search . '%'])->then(function (Clue\React\SQLite\Result $result) {
    echo 'Found ' . count($result->rows) . ' rows: ' . PHP_EOL;
    echo implode("\t", $result->columns) . PHP_EOL;
    foreach ($result->rows as $row) {
        echo implode("\t", $row) . PHP_EOL;
    }
}, function (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
});

$db->quit();
