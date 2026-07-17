<?php

namespace Tests\Gummibeer\Mcp\Tools\Locations;

use Tests\RealWorld\Mcp\Tools\Locations\ListLocationsToolTest as RealWorldListLocationsToolTest;

final class ListLocationsToolTest extends RealWorldListLocationsToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmj78gm6k000004jrvzm7gcjr';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['page' => 2], $campaignId],
            'search' => [['search' => 'hammer'], $campaignId],
        ];
    }
}
