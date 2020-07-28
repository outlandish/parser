<?php

namespace App\Service;

use App\Exception\FileNotFoundException;

class FileLoader implements FileLoaderInterface
{
    /**
     * @param string $pathToFile
     *
     * @return array
     */
    public function getFileContentInRows(string $pathToFile): array
    {
        return explode("\n", trim($this->getFileContent($pathToFile)));
    }

    /**
     * @param string $pathToFile
     *
     * @throws FileNotFoundException
     *
     * @return false|string
     */
    public function getFileContent(string $pathToFile)
    {
        if (file_exists($pathToFile)) {
            return file_get_contents($pathToFile);
        }

        throw new FileNotFoundException();
    }
}