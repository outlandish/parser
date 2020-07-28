<?php

namespace App\Service;

class TotalViewsCalculator
{
    public const VIEWS_KEY = 'views';
    public const UNIQUE_VIEWS_KEY = 'uniqueViews';

    /**
     * @var ParserHelper $parserHelper
     */
    private $parserHelper;

    public function __construct(ParserHelper $helper)
    {
        $this->parserHelper = $helper;
    }

    /**
     * @param array $rows
     *
     * @return array
     */
    public function getTotalViewsCountSorted(array $rows): array
    {
        $uniqueViews = [];
        $viewsCount = [];
        $uniqueViewsCount = [];

        foreach ($rows as $row) {
            list($url, $ip) = preg_split("/\s+/", $row);

            $viewsCount[$url] = $this->parserHelper->getNextViewsCount($viewsCount, $url);
            $this->addUniqueView($uniqueViews, $uniqueViewsCount, $url, $ip);
        }

        arsort($viewsCount);
        arsort($uniqueViewsCount);

        return [
            self::VIEWS_KEY => $viewsCount,
            self::UNIQUE_VIEWS_KEY => $uniqueViewsCount,
        ];
    }

    /**
     * @param array $uniqueViews
     * @param array $uniqueViewsCount
     * @param string $url
     * @param string $ip
     */
    private function addUniqueView(
        array &$uniqueViews,
        array &$uniqueViewsCount,
        string $url,
        string $ip
    ) {
        if (!isset($uniqueViews[$url])) {
            $uniqueViews[$url] = [];
        }

        if (!in_array($ip, $uniqueViews[$url])) {
            $uniqueViews[$url][] = $ip;
            $uniqueViewsCount[$url] = $this->parserHelper->getNextViewsCount($uniqueViewsCount, $url);
        }
    }
}
