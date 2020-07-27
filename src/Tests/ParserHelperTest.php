<?php

namespace App\Tests;

use App\Service\ParserHelper;
use PHPUnit\Framework\TestCase;

class ParserHelperTest extends TestCase
{
    /**
     * @dataProvider viewsProvider
     */
    public function testNextViewsCount($views, $url, $expectedResult)
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
            [[], 'google.com', 0],
        ];
    }

}