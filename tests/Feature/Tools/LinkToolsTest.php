<?php

namespace Tests\Feature\Tools;

use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Links\ListLinksTool;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LinkToolsTest extends TestCase
{
    private array $linkFixture = [
        'id' => 'link_1',
        'campaign_id' => 'camp_1',
        'from_id' => 'char_1',
        'from_type' => 'Character',
        'to_id' => 'char_2',
        'to_type' => 'Character',
        'alias' => 'friends',
        'created_at' => '2024-01-01T00:00:00Z',
    ];

    public function test_list_links_requires_campaign_id(): void
    {
        ArchivistServer::tool(ListLinksTool::class)
            ->assertHasErrors()
            ->assertSee('campaign_id');
    }

    public function test_list_links_returns_structured_data(): void
    {
        Http::fake([
            '*/v1/campaigns/camp_1/links*' => Http::response([
                'data' => [$this->linkFixture], 'total' => 1, 'page' => 1, 'size' => 20, 'pages' => 1,
            ]),
        ]);

        ArchivistServer::tool(ListLinksTool::class, ['campaign_id' => 'camp_1'])
            ->assertOk()
            ->assertSee('link_1');
    }

    public function test_list_links_passes_filters_to_api(): void
    {
        Http::fake([
            '*/v1/campaigns/camp_1/links*' => Http::response([
                'data' => [], 'total' => 0, 'page' => 1, 'size' => 20, 'pages' => 0,
            ]),
        ]);

        ArchivistServer::tool(ListLinksTool::class, [
            'campaign_id' => 'camp_1',
            'from_id' => 'char_1',
            'from_type' => 'Character',
            'alias' => 'friends',
        ])->assertOk();

        Http::assertSent(fn ($req) => str_contains($req->url(), 'from_id=char_1') &&
            str_contains($req->url(), 'from_type=Character') &&
            str_contains($req->url(), 'alias=friends')
        );
    }

    public function test_list_links_returns_error_on_api_failure(): void
    {
        Http::fake([
            '*/v1/campaigns/camp_1/links*' => Http::response(['detail' => 'Forbidden'], 403),
        ]);

        ArchivistServer::tool(ListLinksTool::class, ['campaign_id' => 'camp_1'])
            ->assertHasErrors()
            ->assertSee('Forbidden');
    }
}
