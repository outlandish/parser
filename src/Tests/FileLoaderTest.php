<?php

namespace App\Tests;

use App\Exception\FileNotFoundException;
use App\Service\FileLoader;
use PHPUnit\Framework\TestCase;

class FileLoaderTest extends TestCase
{
    public function testFileLoaderException()
    {
        $this->expectException(FileNotFoundException::class);

        $fileLoader = new FileLoader();
        $fileLoader->getFileContent('file_doesnt_exist.txt');
    }

    public function testFileLoaderGetContent()
    {
        $fileLoader = new FileLoader();

        $this->assertNotFalse($fileLoader->getFileContent($this->getResourcesPath() . 'test.txt'));
    }

    /**
     * @return string
     */
    private function getResourcesPath(): string
    {
        return dirname(__FILE__) . '/resources/';
    }
}