<?php

namespace App\Service;

use Symfony\Component\Console\Output\OutputInterface;

class OutputWriter
{
    public const VIEWS_MESSAGE_FORMAT = '%s - %d visits';
    public const UNIQUE_VIEWS_MESSAGE_FORMAT = '%s - %d unique visits';

    /**
     * @param array $viewsCount
     * @param array $uniqueViewsCount
     * @param OutputInterface $output
     */
    public function outputViewsCount(array $viewsCount, array $uniqueViewsCount, OutputInterface $output)
    {
        $output->writeln([
            '',
            'Views',
            '============',
        ]);

        $this->writeViewsCount($viewsCount, $output, self::VIEWS_MESSAGE_FORMAT);

        $output->writeln([
            '',
            'Unique Views',
            '============',
        ]);

        $this->writeViewsCount($uniqueViewsCount, $output, self::UNIQUE_VIEWS_MESSAGE_FORMAT);
    }

    /**
     * @param $viewsCount
     * @param OutputInterface $output
     * @param string $messageFormat
     */
    private function writeViewsCount($viewsCount, OutputInterface $output, string $messageFormat)
    {
        foreach ($viewsCount as $url => $count) {
            $output->writeln(
                sprintf(
                    $messageFormat,
                    $url,
                    $count
                )
            );
        }
    }
}
