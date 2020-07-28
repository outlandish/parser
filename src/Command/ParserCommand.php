<?php

namespace App\Command;

use App\Exception\FileNotFoundException;
use App\Service\FileLoader;
use App\Service\ParserHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParserCommand extends Command
{
    /**
     * @var ParserHelper $parserHelper
     */
    private $parserHelper;
    /**
     * @var FileLoader $fileLoader
     */
    private $fileLoader;
    /**
     * @var string
     */
    protected static $defaultName = 'app:parse';

    /**
     * @param ParserHelper $parserHelper
     * @param FileLoader $fileLoader
     * @param string|null $name
     */
    public function __construct(ParserHelper $parserHelper, FileLoader $fileLoader, string $name = null)
    {
        $this->parserHelper = $parserHelper;
        $this->fileLoader = $fileLoader;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Parse a log file')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to a file')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $viewsCount = [];
        $uniqueViews = [];
        $uniqueViewsCount = [];

        $pathToFile = $input->getArgument('path');

        try {
            $rows = $this->fileLoader->getFileContentInRows($pathToFile);
        } catch (FileNotFoundException $e) {
            $this->showFileNotFoundMessage($pathToFile, $output);

            return Command::FAILURE;
        }

        foreach ($rows as $row) {
            list($url, $ip) = preg_split("/\s+/", $row);

            $viewsCount[$url] = $this->parserHelper->getNextViewsCount($viewsCount, $url);

            if (!isset($uniqueViews[$url])) {
                $uniqueViews[$url] = [];
            }

            if (!in_array($ip, $uniqueViews[$url])) {
                $uniqueViews[$url][] = $ip;
                $uniqueViewsCount[$url] = $this->parserHelper->getNextViewsCount($uniqueViewsCount, $url);
            }
        }

        arsort($viewsCount);
        arsort($uniqueViewsCount);

        $this->outputViewsCounts($viewsCount, $output);
        $this->outputUniqueViewsCount($uniqueViewsCount, $output);

        return Command::SUCCESS;
    }

    /**
     * @param array $viewsCount
     * @param OutputInterface $output
     */
    private function outputViewsCounts(array $viewsCount, OutputInterface $output)
    {
        $output->writeln([
            '',
            'Views',
            '============',
        ]);

        foreach ($viewsCount as $url => $count) {
            $output->writeln(
                sprintf(
                    '%s - %d visits',
                    $url,
                    $count
                )
            );
        }
    }

    /**
     * @param array $uniqueViews
     * @param OutputInterface $output
     */
    private function outputUniqueViewsCount(array $uniqueViews, OutputInterface $output)
    {
        $output->writeln([
            '',
            'Unique Views',
            '============',
        ]);

        foreach ($uniqueViews as $url => $count) {
            $output->writeln(
                sprintf(
                    '%s - %d unique views',
                    $url,
                    $count
                )
            );
        }
    }

    /**
     * @param string $pathToFile
     * @param OutputInterface $output
     */
    private function showFileNotFoundMessage(string $pathToFile, OutputInterface $output)
    {
        $output->writeln([
            '============',
            'File ' . $pathToFile . ' not found, please check the path is correct',
            '============',
        ]);
    }
}
