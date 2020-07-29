<?php

namespace App\Service;

use App\Exception\FileIsEmptyException;
use App\Exception\FileNotFoundException;

class FileLoader implements FileLoaderInterface
{
    /**
     * @var FileValidator $validator
     */
    private $validator;

    /**
     * @param FileValidator $validator
     */
    public function __construct(FileValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param string $pathToFile
     *
     * @throws FileNotFoundException
     * @throws FileIsEmptyException
     *
     * @return false|string
     */
    public function getContent(string $pathToFile)
    {
        $this->validator->validateFile($pathToFile);
        return file_get_contents($pathToFile);
    }
}