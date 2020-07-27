<?php
// application.php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Command\ParserCommand;
use App\Service\ParserHelper;

$application = new Application();
$application->add(new ParserCommand(new ParserHelper()));

$application->run();