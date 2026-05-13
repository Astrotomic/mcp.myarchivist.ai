<?php

namespace Tests\Feature\Tools;

use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Campaigns\GetCampaignStatsTool;
use App\Mcp\Tools\Campaigns\GetCampaignTool;
use App\Mcp\Tools\Campaigns\ListCampaignsTool;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CampaignToolsTest extends TestCase
{
    // --- ListCampaignsTool ---

    public function test_list_campaigns_returns_structured_data(): void
    {
        Http::fake([
            '*/v1/campaigns*' => Http::response([
                'data' => [['id' => 'camp_1', 'title' => 'Test', 'description' => null, 'system' => 'D&D', 'public' => false, 'created_at' => '2024-01-01T00:00:00Z']],
                'total' => 1,
                'page' => 1,
                'size' => 20,
                'pages' => 1,
            ]),
        ]);

        $response = ArchivistServer::tool(ListCampaignsTool::class);

        $response->assertOk()->assertSee('Test');
    }

    public function test_list_campaigns_passes_page_and_size_to_api(): void
    {
        Http::fake([
            '*/v1/campaigns*' => Http::response([
                'data' => [], 'total' => 0, 'page' => 2, 'size' => 5, 'pages' => 0,
            ]),
        ]);

        ArchivistServer::tool(ListCampaignsTool::class, ['page' => 2, 'size' => 5])->assertOk();

        Http::assertSent(fn ($req) => str_contains($req->url(), 'page=2') && str_contains($req->url(), 'size=5'));
    }

    public function test_list_campaigns_returns_error_on_api_failure(): void
    {
        Http::fake([
            '*/v1/campaigns*' => Http::response(['detail' => 'Invalid API key'], 401),
        ]);

        ArchivistServer::tool(ListCampaignsTool::class)
            ->assertHasErrors()
            ->assertSee('Invalid API key');
    }

    /** Regression: verify the tool reports an error when the HTTP call is removed. */
    public function test_list_campaigns_regression_catches_missing_http_call(): void
    {
        Http::fake([
            '*/v1/campaigns*' => Http::response(['detail' => 'Service down'], 503),
        ]);

        $response = ArchivistServer::tool(ListCampaignsTool::class);

        // Must be an error response — not OK — when the API returns 5xx.
        $response->assertHasErrors();
    }

    // --- GetCampaignTool ---

    public function test_get_campaign_returns_campaign_by_id(): void
    {
        Http::fake([
            '*/v1/campaigns/camp_1' => Http::response([
                'id' => 'camp_1', 'title' => 'Shadows', 'description' => null,
                'system' => null, 'public' => false, 'created_at' => '2024-01-01T00:00:00Z',
            ]),
        ]);

        ArchivistServer::tool(GetCampaignTool::class, ['campaign_id' => 'camp_1'])
            ->assertOk()
            ->assertSee('camp_1');
    }

    public function test_get_campaign_requires_campaign_id(): void
    {
        ArchivistServer::tool(GetCampaignTool::class)
            ->assertHasErrors()
            ->assertSee('campaign_id');
    }

    public function test_get_campaign_returns_error_on_404(): void
    {
        Http::fake([
            '*/v1/campaigns/missing' => Http::response(['detail' => 'Campaign not found'], 404),
        ]);

        ArchivistServer::tool(GetCampaignTool::class, ['campaign_id' => 'missing'])
            ->assertHasErrors()
            ->assertSee('Campaign not found');
    }

    // --- GetCampaignStatsTool ---

    public function test_get_campaign_stats_returns_counts(): void
    {
        Http::fake([
            '*/v1/campaigns/camp_1/stats' => Http::response([
                'campaignId' => 'camp_1', 'characters' => 5, 'sessions' => 10,
                'moments' => 50, 'beats' => 20, 'factions' => 3, 'locations' => 8, 'items' => 12,
            ]),
        ]);

        ArchivistServer::tool(GetCampaignStatsTool::class, ['campaign_id' => 'camp_1'])
            ->assertOk()
            ->assertSee('camp_1');
    }

    public function test_get_campaign_stats_requires_campaign_id(): void
    {
        ArchivistServer::tool(GetCampaignStatsTool::class)
            ->assertHasErrors()
            ->assertSee('campaign_id');
    }
}
