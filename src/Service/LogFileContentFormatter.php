<?php

namespace App\Service;

class LogFileContentFormatter
{
    /**
     * @param string $content
     *
     * @return array
     */
    public function getFileContentInRows(string $content): array
    {
        return explode("\n", trim($content));
    }
}