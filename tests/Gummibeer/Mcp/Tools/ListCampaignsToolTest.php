<?php

namespace Tests\Gummibeer\Mcp\Tools;

use App\Mcp\Data\CampaignData;
use App\Mcp\Data\CampaignDataShort;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Campaigns\ListCampaignsTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\Gummibeer\TestCase;

final class ListCampaignsToolTest extends TestCase
{
    #[Test]
    public function it_lists_campaigns(): void
    {
        $response = ArchivistServer::tool(ListCampaignsTool::class);
        $response
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertPaginatedList(function (AssertableJson $campaign): void {
                        $campaign
                            ->assertStructuredData(CampaignDataShort::class)
                            ->where('owner_id', '4ee2e6b8-698d-4452-82fd-92ca1d1f4642');
                    })
                    ->where('total', 1)
                    ->where('page', 1)
                    ->where('size', 20)
                    ->where('pages', 1)
                    ->count('data', 1);
            });
    }
}
