<?php

namespace Tests\Archivist\Mcp\Tools\Campaigns;

use Tests\RealWorld\Mcp\Tools\Campaigns\GetCampaignStatsToolTest as RealWorldGetCampaignStatsToolTest;

final class GetCampaignStatsToolTest extends RealWorldGetCampaignStatsToolTest
{
    public static function campaignDataProvider(): array
    {
        return [
            'campaign' => ['cmr9ilyjy00020ahu90yhvzx6'],
        ];
    }
}
