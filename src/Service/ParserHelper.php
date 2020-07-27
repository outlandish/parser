<?php

namespace App\Service;

class ParserHelper
{
    /**
     * @param array $viewsCount
     * @param string $url
     *
     * @return int
     */
    public function getNextViewsCount(array $viewsCount, string $url): int
    {
        return isset($viewsCount[$url]) ? $viewsCount[$url] + 1 : 0;
    }
}