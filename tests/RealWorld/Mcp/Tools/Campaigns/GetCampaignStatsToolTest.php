<?php

namespace Tests\RealWorld\Mcp\Tools\Campaigns;

use App\Data\CampaignStatsData;
use App\Mcp\Tools\Campaigns\GetCampaignStatsTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetCampaignStatsToolTest extends RealWorldTestCase
{
    abstract public static function campaignDataProvider(): array;

    #[Test]
    #[DataProvider('campaignDataProvider')]
    public function it_fetches_data(string $campaignId): void
    {
        $this->assertToolReturnsData(
            GetCampaignStatsTool::class,
            ['campaign_id' => $campaignId],
            CampaignStatsData::class,
            ['campaign_id' => $campaignId],
        );
    }
}
