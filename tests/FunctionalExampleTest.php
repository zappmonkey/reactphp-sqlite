<?php

namespace Clue\Tests\React\SQLite;

use PHPUnit\Framework\TestCase;

class FunctionalExampleTest extends TestCase
{
    public function testQueryExampleReturnsDefaultValue()
    {
        $output = $this->execExample(escapeshellarg(PHP_BINARY) . ' query.php');

        $this->assertEquals('value' . PHP_EOL . '42' . PHP_EOL, $output);
    }

    public function testQueryExampleReturnsCalculatedValueFromPlaceholderVariables()
    {
        $output = $this->execExample(escapeshellarg(PHP_BINARY) . ' query.php "SELECT ?+? AS result" 1 2');

        $this->assertEquals('result' . PHP_EOL . '3' . PHP_EOL, $output);
    }

    public function testQueryExampleExecutedWithCgiReturnsDefaultValueAfterContentTypeHeader()
    {
        $code = 1;
        $null = DIRECTORY_SEPARATOR === '\\' ? 'NUL' : '/dev/null';
        system("php-cgi --version >$null 2>$null", $code);
        if ($code !== 0) {
            $this->markTestSkipped('Unable to execute "php-cgi"');
        }

        $output = $this->execExample('php-cgi query.php');

        $this->assertStringEndsWith("\r\n\r\n" . 'value' . PHP_EOL . '42' . PHP_EOL, $output);
    }

    private function execExample($command)
    {
        chdir(__DIR__ . '/../examples/');

        return shell_exec($command);
    }
}
