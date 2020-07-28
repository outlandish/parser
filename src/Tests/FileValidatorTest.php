<?php

namespace App\Tests;

use App\Exception\FileNotFoundException;
use App\Service\FileValidator;
use PHPUnit\Framework\TestCase;

class FileValidatorTest extends TestCase
{
    use ResourcesPathTrait;

    public function testFileLoaderException()
    {
        $this->expectException(FileNotFoundException::class);

        $fileValidator = new FileValidator();
        $fileValidator->validateFile('file_doesnt_exist.txt');
    }

    public function testFileLoaderGetContent()
    {
        $fileValidator = new FileValidator();

        $this->assertNull($fileValidator->validateFile($this->getResourcesPath() . 'test.txt'));
    }
}