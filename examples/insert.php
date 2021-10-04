<?php

require __DIR__ . '/../vendor/autoload.php';

$factory = new Clue\React\SQLite\Factory();

$n = isset($argv[1]) ? $argv[1] : 1;
$db = $factory->openLazy('test.db');

$promise = $db->exec('CREATE TABLE IF NOT EXISTS foo (id INTEGER PRIMARY KEY AUTOINCREMENT, bar STRING)');
$promise->then(null, 'printf');

for ($i = 0; $i < $n; ++$i) {
    $db->exec("INSERT INTO foo (bar) VALUES ('This is a test')")->then(function (Clue\React\SQLite\Result $result) {
        echo 'New row ' . $result->insertId . PHP_EOL;
    }, function (Exception $e) {
        echo 'Error: ' . $e->getMessage() . PHP_EOL;
    });
}

$db->quit();
