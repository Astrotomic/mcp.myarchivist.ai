<?php

namespace Tests\Feature\Tools;

use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Beats\GetBeatTool;
use App\Mcp\Tools\Beats\ListBeatsTool;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BeatToolsTest extends TestCase
{
    private array $beatFixture = [
        'id' => 'beat_1',
        'campaign_id' => 'camp_1',
        'game_session_id' => 'sess_1',
        'label' => 'The Final Battle',
        'type' => 'major',
        'description' => null,
        'index' => 1,
        'parent_id' => null,
        'created_at' => '2024-01-20T22:30:00Z',
    ];

    public function test_list_beats_requires_campaign_id(): void
    {
        ArchivistServer::tool(ListBeatsTool::class)
            ->assertHasErrors()
            ->assertSee('campaign_id');
    }

    public function test_list_beats_returns_structured_data(): void
    {
        Http::fake([
            '*/v1/beats*' => Http::response([
                'data' => [$this->beatFixture], 'total' => 1, 'page' => 1, 'size' => 20, 'pages' => 1,
            ]),
        ]);

        ArchivistServer::tool(ListBeatsTool::class, ['campaign_id' => 'camp_1'])
            ->assertOk()
            ->assertSee('The Final Battle');
    }

    public function test_get_beat_requires_beat_id(): void
    {
        ArchivistServer::tool(GetBeatTool::class)
            ->assertHasErrors()
            ->assertSee('beat_id');
    }

    public function test_get_beat_returns_beat_by_id(): void
    {
        Http::fake([
            '*/v1/beats/beat_1' => Http::response($this->beatFixture),
        ]);

        ArchivistServer::tool(GetBeatTool::class, ['beat_id' => 'beat_1'])
            ->assertOk()
            ->assertSee('beat_1');
    }

    public function test_list_beats_returns_error_on_api_failure(): void
    {
        Http::fake([
            '*/v1/beats*' => Http::response(['detail' => 'Unauthorized'], 401),
        ]);

        ArchivistServer::tool(ListBeatsTool::class, ['campaign_id' => 'camp_1'])
            ->assertHasErrors()
            ->assertSee('Unauthorized');
    }
}
