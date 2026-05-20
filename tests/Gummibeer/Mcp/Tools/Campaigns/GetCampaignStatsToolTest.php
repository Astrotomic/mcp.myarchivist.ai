<?php

namespace Tests\Gummibeer\Mcp\Tools\Campaigns;

use App\Data\CampaignStatsData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Campaigns\GetCampaignStatsTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\Gummibeer\TestCase;

final class GetCampaignStatsToolTest extends TestCase
{
    #[Test]
    public function it_fetches_data(): void
    {
        ArchivistServer::tool(GetCampaignStatsTool::class, [
            'campaign_id' => 'cmj78gm6k000004jrvzm7gcjr',
        ])
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertJsonSchema(CampaignStatsData::class)
                    ->where('campaign_id', 'cmj78gm6k000004jrvzm7gcjr');
            });
    }
}
