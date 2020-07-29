<?php

namespace App\Tests;

use App\Service\FileLoader;
use App\Service\FileValidator;
use App\Service\LogFileContentFormatter;
use App\Service\OutputWriter;
use App\Service\ParserHelper;
use App\Service\TotalViewsCalculator;
use PHPUnit\Framework\TestCase;
use App\Command\ParserCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ParserCommandTest extends TestCase
{
    use ResourcesPathTrait;

    public function testExecute()
    {
        $application = new Application();
        $application->add(new ParserCommand(
            new TotalViewsCalculator(new ParserHelper()),
            new FileLoader(new FileValidator()),
            new LogFileContentFormatter(),
            new OutputWriter()
        ));
        $command = $application->find('app:parse');
        $commandTester = new CommandTester($command);

        $commandTester->execute(['path' => $this->getResourcesPath() . 'webserver.log']);

        $this->assertStringContainsString('Views', $commandTester->getDisplay());
    }
}