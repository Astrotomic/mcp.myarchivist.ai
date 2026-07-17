<?php

namespace Tests\Gummibeer\Mcp\Tools\Campaigns;

use Tests\RealWorld\Mcp\Tools\Campaigns\GetCampaignToolTest as RealWorldGetCampaignToolTest;

final class GetCampaignToolTest extends RealWorldGetCampaignToolTest
{
    public static function campaignDataProvider(): array
    {
        return [
            'campaign' => ['cmj78gm6k000004jrvzm7gcjr'],
        ];
    }
}
