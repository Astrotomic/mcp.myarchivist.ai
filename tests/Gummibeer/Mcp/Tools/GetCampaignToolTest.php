<?php

namespace Tests\Gummibeer\Mcp\Tools;

use App\Mcp\Data\CampaignData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Campaigns\GetCampaignTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\Gummibeer\TestCase;

final class GetCampaignToolTest extends TestCase
{
    #[Test]
    public function it_lists_campaigns(): void
    {
        $response = ArchivistServer::tool(GetCampaignTool::class, [
            'campaign_id' =>'cmj78gm6k000004jrvzm7gcjr'
        ]);
        $response->dump();
        $response
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertStructuredData(CampaignData::class)
                    ->where('owner_id', '4ee2e6b8-698d-4452-82fd-92ca1d1f4642');

            });
    }
}
