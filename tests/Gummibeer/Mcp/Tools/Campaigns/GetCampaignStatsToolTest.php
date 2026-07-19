<?php

namespace Tests\Gummibeer\Mcp\Tools\Campaigns;

use Tests\RealWorld\Mcp\Tools\Campaigns\GetCampaignStatsToolTest as RealWorldGetCampaignStatsToolTest;

final class GetCampaignStatsToolTest extends RealWorldGetCampaignStatsToolTest
{
    public static function campaignDataProvider(): array
    {
        return [
            'campaign' => ['cmj78gm6k000004jrvzm7gcjr'],
        ];
    }
}
