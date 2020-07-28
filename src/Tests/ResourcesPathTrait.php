<?php

namespace App\Tests;

trait ResourcesPathTrait
{
    /**
     * @return string
     */
    private function getResourcesPath(): string
    {
        return dirname(__FILE__) . '/resources/';
    }
}