<?php

namespace Clue\Tests\React\SQLite;

use Clue\React\SQLite\DatabaseInterface;
use Clue\React\SQLite\Factory;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Loop;

class FunctionalFactoryTest extends TestCase
{
    public function testOpenReturnsPromiseWhichFulfillsWithConnectionForMemoryPath()
    {
        $factory = new Factory();
        $promise = $factory->open(':memory:');

        $promise->then(function (DatabaseInterface $db) {
            echo 'open.';
            $db->on('close', function () {
                echo 'close.';
            });

            $db->close();
        }, function (\Exception $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        });

        $this->expectOutputString('open.close.');
        Loop::run();
    }

    public function testOpenReturnsPromiseWhichFulfillsWithConnectionForMemoryPathAndExplicitPhpBinary()
    {
        $factory = new Factory(null, PHP_BINARY);
        $promise = $factory->open(':memory:');

        $promise->then(function (DatabaseInterface $db) {
            echo 'open.';
            $db->on('close', function () {
                echo 'close.';
            });

                $db->close();
        }, function (\Exception $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        });

        $this->expectOutputString('open.close.');
        Loop::run();
    }

    public function testOpenReturnsPromiseWhichRejectsWithExceptionWhenPathIsInvalid()
    {
        $factory = new Factory();
        $promise = $factory->open('/dev/foobar');

        $promise->then(function (DatabaseInterface $db) {
            echo 'open.';
            $db->close();
        }, function (\Exception $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        });

        $this->expectOutputString('Error: Unable to open database: unable to open database file' . PHP_EOL);
        Loop::run();
    }

    public function testOpenReturnsPromiseWhichRejectsWithExceptionWhenExplicitPhpBinaryExitsImmediately()
    {
        $factory = new Factory(null, 'echo');

        $ref = new \ReflectionProperty($factory, 'useSocket');
        $ref->setAccessible(true);
        $ref->setValue($factory, true);

        $promise = $factory->open(':memory:');

        $promise->then(function (DatabaseInterface $db) {
            echo 'open.';
            $db->close();
        }, function (\Exception $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        });

        $this->expectOutputString('Error: No connection detected' . PHP_EOL);
        Loop::run();
    }
}
