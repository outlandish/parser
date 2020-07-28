<?php

namespace App\Service;

interface FileLoaderInterface
{
    /**
     * @param string $pathToFile
     */
    public function getContent(string $pathToFile);
}