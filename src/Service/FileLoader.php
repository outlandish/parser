<?php

namespace App\Service;

use App\Exception\FileNotFoundException;

class FileLoader implements FileLoaderInterface
{
    /**
     * @param string $pathToFile
     *
     * @return false|string
     */
    public function getContent(string $pathToFile)
    {
        return file_get_contents($pathToFile);
    }
}