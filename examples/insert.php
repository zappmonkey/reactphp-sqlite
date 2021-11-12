<?php

require __DIR__ . '/../vendor/autoload.php';

$factory = new Clue\React\SQLite\Factory();
$db = $factory->openLazy(__DIR__ . '/users.db');

$db->exec(
    'CREATE TABLE IF NOT EXISTS user (id INTEGER PRIMARY KEY AUTOINCREMENT, name STRING)'
)->then(null, function (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
});

$n = isset($argv[1]) ? $argv[1] : 1;
for ($i = 0; $i < $n; ++$i) {
    $db->exec("INSERT INTO user (name) VALUES ('Alice')")->then(function (Clue\React\SQLite\Result $result) {
        echo 'New row ' . $result->insertId . PHP_EOL;
    }, function (Exception $e) {
        echo 'Error: ' . $e->getMessage() . PHP_EOL;
    });
}

$db->quit();
