<?php

// $ php examples/query.php "INSERT INTO user (name) VALUES ('Bob'),('Carol')"
// $ php examples/query.php "DELETE FROM user WHERE name = ?" "Carol"

require __DIR__ . '/../vendor/autoload.php';

$factory = new Clue\React\SQLite\Factory();
$db = $factory->openLazy(__DIR__ . '/users.db');

$query = isset($argv[1]) ? $argv[1] : 'SELECT 42 AS value';
$args = array_slice(isset($argv) ? $argv : [], 2);

$db->query($query, $args)->then(function (Clue\React\SQLite\Result $result) {
    if ($result->columns !== null) {
        echo implode("\t", $result->columns) . PHP_EOL;
        foreach ($result->rows as $row) {
            echo implode("\t", $row) . PHP_EOL;
        }
    } else {
        echo "changed\tid". PHP_EOL;
        echo $result->changed . "\t" . $result->insertId . PHP_EOL;
    }
}, function (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
});

$db->quit();
