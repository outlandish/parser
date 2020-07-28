<?php

namespace App\Command;

use App\Service\FileLoader;
use App\Service\FileLoaderInterface;
use App\Service\FileValidator;
use App\Service\LogFileContentFormatter;
use App\Service\OutputWriter;
use App\Service\TotalViewsCalculator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParserCommand extends Command
{
    /**
     * @var TotalViewsCalculator $calculator
     */
    private $calculator;
    /**
     * @var FileLoader $fileLoader
     */
    private $fileLoader;
    /**
     * @var FileLoader $formatter
     */
    private $formatter;
    /**
     * @var FileValidator $validator
     */
    private $validator;
    /**
     * @var OutputWriter $writer
     */
    private $writer;

    /**
     * @param TotalViewsCalculator $calculator
     * @param FileLoaderInterface $fileLoader
     * @param LogFileContentFormatter $formatter
     * @param FileValidator $validator
     * @param OutputWriter $writer
     * @param string|null $name
     */
    public function __construct(
        TotalViewsCalculator $calculator,
        FileLoaderInterface $fileLoader,
        LogFileContentFormatter $formatter,
        FileValidator $validator,
        OutputWriter $writer,
        string $name = null
    ) {
        $this->calculator = $calculator;
        $this->fileLoader = $fileLoader;
        $this->formatter = $formatter;
        $this->validator = $validator;
        $this->writer = $writer;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('app:parse')
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
        $pathToFile = $input->getArgument('path');

        try {
            $this->validator->validateFile($pathToFile);

            $content = $this->fileLoader->getContent($pathToFile);
            $rows = $this->formatter->getFileContentInRows($content);
            $views = $this->calculator->getTotalViewsCountSorted($rows);
        } catch (\Exception $e) {
            $this->showMessageOnException($output, $e);

            return Command::FAILURE;
        }

        $this->writer->outputViewsCount(
            $views[TotalViewsCalculator::VIEWS_KEY],
            $views[TotalViewsCalculator::UNIQUE_VIEWS_KEY],
            $output
        );

        return Command::SUCCESS;
    }

    /**
     * @param OutputInterface $output
     * @param \Exception $e
     */
    private function showMessageOnException(OutputInterface $output, \Exception $e)
    {
        $output->writeln([
            '============',
            $e->getMessage(),
            '============',
        ]);
    }
}
