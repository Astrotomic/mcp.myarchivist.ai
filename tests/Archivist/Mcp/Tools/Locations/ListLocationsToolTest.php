<?php

namespace Tests\Archivist\Mcp\Tools\Locations;

use Tests\RealWorld\Mcp\Tools\Locations\ListLocationsToolTest as RealWorldListLocationsToolTest;

final class ListLocationsToolTest extends RealWorldListLocationsToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmr9ilyjy00020ahu90yhvzx6';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['page' => 2], $campaignId],
            'search' => [['search' => 'Tower'], $campaignId],
        ];
    }
}
