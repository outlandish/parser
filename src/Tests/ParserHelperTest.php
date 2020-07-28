<?php

namespace App\Tests;

use App\Service\ParserHelper;
use PHPUnit\Framework\TestCase;

class ParserHelperTest extends TestCase
{
    /**
     * @dataProvider viewsProvider
     *
     * @param array $views
     * @param string $url
     * @param int $expectedResult
     */
    public function testNextViewsCount(array $views, string $url, int $expectedResult)
    {
        $helper = new ParserHelper();
        $result = $helper->getNextViewsCount($views, $url);

        $this->assertEquals($expectedResult, $result);
    }

    public function viewsProvider()
    {
        return [
            [
                ['google.com' => 5],
                'google.com',
                6,
            ],
            [
                [],
                'google.com',
                1,
            ],
        ];
    }

}