<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Command\ParserCommand;
use App\Service\FileLoader;
use App\Service\TotalViewsCalculator;
use App\Service\LogFileContentFormatter;
use App\Service\FileValidator;
use App\Service\OutputWriter;
use App\Service\ParserHelper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$application = new Application();
$application->add(
    new ParserCommand(
        new TotalViewsCalculator(new ParserHelper()),
        new FileLoader(new FileValidator()),
        new LogFileContentFormatter(),
        new OutputWriter()
    )
);

$application->run();
