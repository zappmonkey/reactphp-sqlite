<?php

namespace Clue\Tests\React\SQLite;

use PHPUnit\Framework\TestCase;
use Clue\React\SQLite\Factory;

class FactoryTest extends TestCase
{
    public function testConstructWithoutLoopAssignsLoopAutomatically()
    {
        $factory = new Factory();

        $ref = new \ReflectionProperty($factory, 'loop');
        $ref->setAccessible(true);
        $loop = $ref->getValue($factory);

        $this->assertInstanceOf('React\EventLoop\LoopInterface', $loop);
    }

    public function testConstructWitLoopAndBinaryAssignsBothVariables()
    {
        $loop = $this->getMockBuilder('React\EventLoop\LoopInterface')->getMock();
        $factory = new Factory($loop, 'php6.0');

        $ref = new \ReflectionProperty($factory, 'loop');
        $ref->setAccessible(true);
        $this->assertSame($loop, $ref->getValue($factory));

        $ref = new \ReflectionProperty($factory, 'bin');
        $ref->setAccessible(true);
        $this->assertSame('php6.0', $ref->getValue($factory));
    }

    public function testLoadLazyReturnsDatabaseImmediately()
    {
        $loop = $this->getMockBuilder('React\EventLoop\LoopInterface')->getMock();
        $factory = new Factory($loop);

        $db = $factory->openLazy(':memory:');

        $this->assertInstanceOf('Clue\React\SQLite\DatabaseInterface', $db);
    }

    public function testLoadLazyWithIdleOptionsReturnsDatabaseWithIdleTimeApplied()
    {
        $loop = $this->getMockBuilder('React\EventLoop\LoopInterface')->getMock();
        $factory = new Factory($loop);

        $db = $factory->openLazy(':memory:', null, ['idle' => 10.0]);

        $ref = new \ReflectionProperty($db, 'idlePeriod');
        $ref->setAccessible(true);
        $value = $ref->getValue($db);

        $this->assertEquals(10.0, $value);
    }

    public function testLoadLazyWithAbsolutePathWillBeUsedAsIs()
    {
        $loop = $this->getMockBuilder('React\EventLoop\LoopInterface')->getMock();
        $factory = new Factory($loop);

        $db = $factory->openLazy(__DIR__ . '/users.db');

        $ref = new \ReflectionProperty($db, 'filename');
        $ref->setAccessible(true);
        $value = $ref->getValue($db);

        $this->assertEquals(__DIR__ . '/users.db', $value);
    }

    public function testLoadLazyWithMemoryPathWillBeUsedAsIs()
    {
        $loop = $this->getMockBuilder('React\EventLoop\LoopInterface')->getMock();
        $factory = new Factory($loop);

        $db = $factory->openLazy(':memory:');

        $ref = new \ReflectionProperty($db, 'filename');
        $ref->setAccessible(true);
        $value = $ref->getValue($db);

        $this->assertEquals(':memory:', $value);
    }

    public function testLoadLazyWithEmptyPathWillBeUsedAsIs()
    {
        $loop = $this->getMockBuilder('React\EventLoop\LoopInterface')->getMock();
        $factory = new Factory($loop);

        $db = $factory->openLazy('');

        $ref = new \ReflectionProperty($db, 'filename');
        $ref->setAccessible(true);
        $value = $ref->getValue($db);

        $this->assertEquals('', $value);
    }

    public function testLoadLazyWithRelativePathWillBeResolvedWhenConstructingAndWillNotBeAffectedByChangingDirectory()
    {
        $original = getcwd();
        if ($original === false) {
            $this->markTestSkipped('Unable to detect current working directory');
        }

        $loop = $this->getMockBuilder('React\EventLoop\LoopInterface')->getMock();
        $factory = new Factory($loop);

        $db = $factory->openLazy('users.db');

        chdir('../');

        $ref = new \ReflectionProperty($db, 'filename');
        $ref->setAccessible(true);
        $value = $ref->getValue($db);

        chdir($original);

        $this->assertEquals($original . DIRECTORY_SEPARATOR . 'users.db', $value);
    }
}
