<?php

namespace Tests\Archivist\Mcp\Tools\Campaigns;

use Tests\RealWorld\Mcp\Tools\Campaigns\GetCampaignToolTest as RealWorldGetCampaignToolTest;

final class GetCampaignToolTest extends RealWorldGetCampaignToolTest
{
    public static function campaignDataProvider(): array
    {
        return [
            'campaign' => ['cmr9ilyjy00020ahu90yhvzx6'],
        ];
    }
}
