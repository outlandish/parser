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
            // Unique views are handled separately
            $this->addUniqueView($uniqueViews, $uniqueViewsCount, $url, $ip);
        }

        // Sorting by views count in descending order
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
        // If there are no views for the given url we assign it an empty array
        if (!isset($uniqueViews[$url])) {
            $uniqueViews[$url] = [];
        }

        // We increase a unique views count only if there is no visit from the url
        if (!in_array($ip, $uniqueViews[$url])) {
            $uniqueViews[$url][] = $ip;
            $uniqueViewsCount[$url] = $this->parserHelper->getNextViewsCount($uniqueViewsCount, $url);
        }
    }
}
