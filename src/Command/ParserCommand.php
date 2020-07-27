<?php

namespace App\Command;

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
     * @var string
     */
    protected static $defaultName = 'app:parse';

    /**
     * @param ParserHelper $parserHelper
     * @param string|null $name
     */
    public function __construct(ParserHelper $parserHelper, string $name = null)
    {
        $this->parserHelper = $parserHelper;

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

        $fileContent = file_get_contents($input->getArgument('path'));
        $rows = explode("\n", trim($fileContent));

        foreach ($rows as $row) {
            list($url, $ip) = preg_split("/\s+/", $row);

            $viewsCount[$url] = $this->parserHelper->getNextViewsCount($viewsCount, $url);

            if (!in_array($ip, $uniqueViews[$url])) {
                $uniqueViews[$url][] = $ip;
                $uniqueViewsCount[$url] = $this->parserHelper->getNextViewsCount($uniqueViewsCount, $url);
            }
        }

        arsort($viewsCount);
        arsort($uniqueViewsCount);

        $this->outputViewsCounts(
            $viewsCount,
            $uniqueViewsCount,
            $output
        );

        return Command::SUCCESS;
    }

    /**
     * @param array $viewsCount
     * @param array $uniqueViews
     * @param OutputInterface $output
     */
    private function outputViewsCounts(array $viewsCount, array $uniqueViews, OutputInterface $output)
    {
        $output->writeln([
            '',
            'Views',
            '============',
        ]);

        foreach ($viewsCount as $url => $count) {
            $output->writeln(sprintf(
                '%s - %d visits',
                $url,
                $count
            ));
        }

        $output->writeln([
            '',
            'Unique Views',
            '============',
        ]);

        foreach ($uniqueViews as $url => $count) {
            $output->writeln(sprintf(
                '%s - %d unique views',
                $url,
                $count
            ));
        }
    }
}
