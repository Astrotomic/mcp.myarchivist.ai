<?php

namespace Tests\Gummibeer\Mcp\Tools\Campaigns;

use App\Data\CampaignData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Campaigns\GetCampaignTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\GummibeerTestCase;

final class GetCampaignToolTest extends GummibeerTestCase
{
    #[Test]
    public function it_fetches_data(): void
    {
        ArchivistServer::tool(GetCampaignTool::class, [
            'campaign_id' => 'cmj78gm6k000004jrvzm7gcjr',
        ])
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertJsonSchema(CampaignData::class)
                    ->where('id', 'cmj78gm6k000004jrvzm7gcjr');

                $this->assertMatchesJsonSnapshot($json);
            });
    }
}
