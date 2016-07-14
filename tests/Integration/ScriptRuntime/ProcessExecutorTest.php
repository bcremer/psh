<?php declare(strict_types = 1);

namespace Shopware\Psh\Test\Unit\Integration\ScriptRuntime;


use Shopware\Psh\Listing\Script;
use Shopware\Psh\ScriptRuntime\CommandBuilder;
use Shopware\Psh\ScriptRuntime\Environment;
use Shopware\Psh\ScriptRuntime\ProcessExecutor;
use Shopware\Psh\ScriptRuntime\ScriptLoader;
use Shopware\Psh\ScriptRuntime\TemplateEngine;
use Shopware\Psh\Test\BlackholeLogger;

class ProcessExecutorTest extends \PHPUnit_Framework_TestCase
{

    public function test_environment_and_export_work()
    {
        $script = new Script(__DIR__ . '/_scripts', 'environment.sh');
        $loader = new ScriptLoader(new CommandBuilder());
        $commands = $loader->loadScript($script);
        $logger = new BlackholeLogger();

        $executor = new ProcessExecutor(
            new Environment([], []),
            new TemplateEngine(),
            $logger
        );

        $executor->execute($script, $commands);

        $this->assertEmpty($logger->errors, count($logger->errors) . ' stderr: ' . implode("\n", $logger->errors));
        $this->assertEquals('', trim($logger->output[0]), count($logger->output) . ' stdout: ' . implode("\n", $logger->output));
    }
}