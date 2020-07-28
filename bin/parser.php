<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Command\ParserCommand;
use App\Service\ParserHelper;
use App\Service\FileLoader;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$application = new Application();
$application->add(new ParserCommand(new ParserHelper(), new FileLoader()));

$application->run();
