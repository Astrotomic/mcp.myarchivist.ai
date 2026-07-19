<?php

namespace Tests\RealWorld\Mcp\Tools\Campaigns;

use App\Data\CampaignData;
use App\Mcp\Tools\Campaigns\GetCampaignTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetCampaignToolTest extends RealWorldTestCase
{
    abstract public static function campaignDataProvider(): array;

    #[Test]
    #[DataProvider('campaignDataProvider')]
    public function it_fetches_data(string $campaignId): void
    {
        $this->assertToolReturnsData(
            GetCampaignTool::class,
            ['campaign_id' => $campaignId],
            CampaignData::class,
            ['id' => $campaignId],
        );
    }
}
