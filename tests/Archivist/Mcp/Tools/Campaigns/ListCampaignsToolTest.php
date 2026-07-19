<?php

namespace Tests\Archivist\Mcp\Tools\Campaigns;

use Tests\RealWorld\Mcp\Tools\Campaigns\ListCampaignsToolTest as RealWorldListCampaignsToolTest;

final class ListCampaignsToolTest extends RealWorldListCampaignsToolTest
{
    public static function queryDataProvider(): array
    {
        $ownerId = 'b9a0a71b-eb62-455a-9e56-a596872a42e2';

        return [
            'no query' => [[], $ownerId],
            'size' => [['size' => 100], $ownerId],
            'page' => [['page' => 1], $ownerId],
        ];
    }
}
