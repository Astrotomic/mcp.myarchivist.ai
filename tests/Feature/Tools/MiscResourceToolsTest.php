<?php

namespace Tests\Feature\Tools;

use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Factions\GetFactionTool;
use App\Mcp\Tools\Factions\ListFactionsTool;
use App\Mcp\Tools\Items\GetItemTool;
use App\Mcp\Tools\Items\ListItemsTool;
use App\Mcp\Tools\Locations\GetLocationTool;
use App\Mcp\Tools\Locations\ListLocationsTool;
use App\Mcp\Tools\Moments\GetMomentTool;
use App\Mcp\Tools\Moments\ListMomentsTool;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MiscResourceToolsTest extends TestCase
{
    // --- Moments ---

    public function test_list_moments_returns_data(): void
    {
        Http::fake([
            '*/v1/moments*' => Http::response([
                'data' => [[
                    'id' => 'moment_1', 'campaign_id' => 'camp_1', 'session_id' => null,
                    'label' => 'Epic Quote', 'content' => 'I am Thorin!', 'created_at' => '2024-01-01T00:00:00Z',
                ]],
                'total' => 1, 'page' => 1, 'size' => 20, 'pages' => 1,
            ]),
        ]);

        ArchivistServer::tool(ListMomentsTool::class, ['campaign_id' => 'camp_1'])
            ->assertOk()
            ->assertSee('Epic Quote');
    }

    public function test_get_moment_requires_moment_id(): void
    {
        ArchivistServer::tool(GetMomentTool::class)->assertHasErrors()->assertSee('moment_id');
    }

    public function test_get_moment_returns_data(): void
    {
        Http::fake([
            '*/v1/moments/moment_1' => Http::response([
                'id' => 'moment_1', 'campaign_id' => 'camp_1', 'session_id' => null,
                'label' => 'Epic Quote', 'content' => 'I am Thorin!', 'created_at' => '2024-01-01T00:00:00Z',
            ]),
        ]);

        ArchivistServer::tool(GetMomentTool::class, ['moment_id' => 'moment_1'])
            ->assertOk()->assertSee('moment_1');
    }

    // --- Factions ---

    public function test_list_factions_requires_campaign_id(): void
    {
        ArchivistServer::tool(ListFactionsTool::class)->assertHasErrors()->assertSee('campaign_id');
    }

    public function test_list_factions_returns_data(): void
    {
        Http::fake([
            '*/v1/factions*' => Http::response([
                'data' => [[
                    'id' => 'faction_1', 'campaign_id' => 'camp_1', 'name' => 'Shadow Thieves',
                    'description' => null, 'type' => 'guild', 'created_at' => '2024-01-01T00:00:00Z',
                ]],
                'total' => 1, 'page' => 1, 'size' => 20, 'pages' => 1,
            ]),
        ]);

        ArchivistServer::tool(ListFactionsTool::class, ['campaign_id' => 'camp_1'])
            ->assertOk()->assertSee('Shadow Thieves');
    }

    public function test_get_faction_requires_faction_id(): void
    {
        ArchivistServer::tool(GetFactionTool::class)->assertHasErrors()->assertSee('faction_id');
    }

    public function test_get_faction_returns_data(): void
    {
        Http::fake([
            '*/v1/factions/faction_1' => Http::response([
                'id' => 'faction_1', 'campaign_id' => 'camp_1', 'name' => 'Shadow Thieves',
                'description' => null, 'type' => 'guild', 'created_at' => '2024-01-01T00:00:00Z',
            ]),
        ]);

        ArchivistServer::tool(GetFactionTool::class, ['faction_id' => 'faction_1'])
            ->assertOk()->assertSee('faction_1');
    }

    // --- Locations ---

    public function test_list_locations_requires_campaign_id(): void
    {
        ArchivistServer::tool(ListLocationsTool::class)->assertHasErrors()->assertSee('campaign_id');
    }

    public function test_list_locations_returns_data(): void
    {
        Http::fake([
            '*/v1/locations*' => Http::response([
                'data' => [[
                    'id' => 'loc_1', 'campaign_id' => 'camp_1', 'name' => 'The Prancing Pony',
                    'description' => null, 'type' => 'tavern', 'parent_id' => null, 'created_at' => '2024-01-01T00:00:00Z',
                ]],
                'total' => 1, 'page' => 1, 'size' => 20, 'pages' => 1,
            ]),
        ]);

        ArchivistServer::tool(ListLocationsTool::class, ['campaign_id' => 'camp_1'])
            ->assertOk()->assertSee('The Prancing Pony');
    }

    public function test_get_location_requires_location_id(): void
    {
        ArchivistServer::tool(GetLocationTool::class)->assertHasErrors()->assertSee('location_id');
    }

    public function test_get_location_returns_data(): void
    {
        Http::fake([
            '*/v1/locations/loc_1' => Http::response([
                'id' => 'loc_1', 'campaign_id' => 'camp_1', 'name' => 'The Prancing Pony',
                'description' => null, 'type' => 'tavern', 'parent_id' => null, 'created_at' => '2024-01-01T00:00:00Z',
            ]),
        ]);

        ArchivistServer::tool(GetLocationTool::class, ['location_id' => 'loc_1'])
            ->assertOk()->assertSee('loc_1');
    }

    // --- Items ---

    public function test_list_items_requires_campaign_id(): void
    {
        ArchivistServer::tool(ListItemsTool::class)->assertHasErrors()->assertSee('campaign_id');
    }

    public function test_list_items_returns_data(): void
    {
        Http::fake([
            '*/v1/items*' => Http::response([
                'data' => [[
                    'id' => 'item_1', 'campaign_id' => 'camp_1', 'name' => 'Sting',
                    'description' => 'An elven blade', 'type' => 'weapon', 'created_at' => '2024-01-01T00:00:00Z',
                ]],
                'total' => 1, 'page' => 1, 'size' => 20, 'pages' => 1,
            ]),
        ]);

        ArchivistServer::tool(ListItemsTool::class, ['campaign_id' => 'camp_1'])
            ->assertOk()->assertSee('Sting');
    }

    public function test_get_item_requires_item_id(): void
    {
        ArchivistServer::tool(GetItemTool::class)->assertHasErrors()->assertSee('item_id');
    }

    public function test_get_item_returns_data(): void
    {
        Http::fake([
            '*/v1/items/item_1' => Http::response([
                'id' => 'item_1', 'campaign_id' => 'camp_1', 'name' => 'Sting',
                'description' => 'An elven blade', 'type' => 'weapon', 'created_at' => '2024-01-01T00:00:00Z',
            ]),
        ]);

        ArchivistServer::tool(GetItemTool::class, ['item_id' => 'item_1'])
            ->assertOk()->assertSee('item_1');
    }
}
