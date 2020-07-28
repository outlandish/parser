<?php

namespace App\Service;

use App\Exception\FileIsEmptyException;
use App\Exception\FileNotFoundException;

class FileValidator
{
    /**
     * @param string $pathToFile
     *
     * @throws FileIsEmptyException
     * @throws FileNotFoundException
     */
    public function validateFile(string $pathToFile)
    {
        if (!file_exists($pathToFile)) {
            throw new FileNotFoundException('File ' . $pathToFile . ' is not found');
        }

        if (!filesize($pathToFile)) {
            throw new FileIsEmptyException('File ' . $pathToFile . ' is empty');
        }
    }
}