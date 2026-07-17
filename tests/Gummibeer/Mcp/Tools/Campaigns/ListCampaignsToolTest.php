<?php

namespace Tests\Gummibeer\Mcp\Tools\Campaigns;

use Tests\RealWorld\Mcp\Tools\Campaigns\ListCampaignsToolTest as RealWorldListCampaignsToolTest;

final class ListCampaignsToolTest extends RealWorldListCampaignsToolTest
{
    public static function queryDataProvider(): array
    {
        $ownerId = '4ee2e6b8-698d-4452-82fd-92ca1d1f4642';

        return [
            'no query' => [[], $ownerId],
            'size' => [['size' => 100], $ownerId],
            'page' => [['page' => 1], $ownerId],
        ];
    }
}
