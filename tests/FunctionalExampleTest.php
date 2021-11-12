<?php

namespace Clue\Tests\React\SQLite;

use PHPUnit\Framework\TestCase;

class FunctionalExampleTest extends TestCase
{
    public function testQueryExampleReturnsDefaultValue()
    {
        $output = $this->execExample(escapeshellarg(PHP_BINARY) . ' query.php');

        $this->assertEquals('value' . "\n" . '42' . "\n", $output);
    }

    public function testQueryExampleReturnsCalculatedValueFromPlaceholderVariables()
    {
        $output = $this->execExample(escapeshellarg(PHP_BINARY) . ' query.php "SELECT ?+? AS result" 1 2');

        $this->assertEquals('result' . "\n" . '3' . "\n", $output);
    }

    public function testQueryExampleExecutedWithCgiReturnsDefaultValueAfterContentTypeHeader()
    {
        if (!$this->canExecute('php-cgi --version')) {
            $this->markTestSkipped('Unable to execute "php-cgi"');
        }

        $output = $this->execExample('php-cgi query.php');

        $this->assertStringEndsWith("\n\n" . 'value' . "\n" . '42' . "\n", $output);
    }

    public function testQueryExampleWithOpenBasedirRestrictedReturnsDefaultValue()
    {
        $output = $this->execExample(escapeshellarg(PHP_BINARY) . ' -dopen_basedir=' . escapeshellarg(dirname(__DIR__)) . ' query.php');

        $this->assertEquals('value' . "\n" . '42' . "\n", $output);
    }

    public function testQueryExampleWithOpenBasedirRestrictedAndAdditionalFileDescriptorReturnsDefaultValue()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            $this->markTestSkipped('Not supported on Windows');
        }

        $output = $this->execExample(escapeshellarg(PHP_BINARY) . ' -dopen_basedir=' . escapeshellarg(dirname(__DIR__)) . ' query.php 3</dev/null');

        $this->assertEquals('value' . "\n" . '42' . "\n", $output);
    }

    public function testQueryExampleExecutedWithCgiAndOpenBasedirRestrictedRunsDefaultPhpAndReturnsDefaultValueAfterContentTypeHeader()
    {
        if (!$this->canExecute('php-cgi --version') || !$this->canExecute('php --version')) {
            $this->markTestSkipped('Unable to execute "php-cgi" or "php"');
        }

        $output = $this->execExample('php-cgi -dopen_basedir=' . escapeshellarg(dirname(__DIR__)) . ' query.php');

        $this->assertStringEndsWith("\n\n" . 'value' . "\n" . '42' . "\n", $output);
    }

    private function canExecute($command)
    {
        $code = 1;
        $null = DIRECTORY_SEPARATOR === '\\' ? 'NUL' : '/dev/null';
        system("$command >$null 2>$null", $code);

        return $code === 0;
    }

    private function execExample($command)
    {
        chdir(__DIR__ . '/../examples/');

        return str_replace("\r\n", "\n", shell_exec($command));
    }
}
