<?php

namespace Tests\Gummibeer\Mcp\Tools\Moments;

use Tests\RealWorld\Mcp\Tools\Moments\ListMomentsToolTest as RealWorldListMomentsToolTest;

final class ListMomentsToolTest extends RealWorldListMomentsToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmj78gm6k000004jrvzm7gcjr';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['page' => 2], $campaignId],
            'search' => [['search' => 'schiff'], $campaignId],
        ];
    }
}
