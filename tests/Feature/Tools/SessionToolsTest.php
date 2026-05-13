<?php

namespace Tests\Feature\Tools;

use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Sessions\GetSessionCastAnalysisTool;
use App\Mcp\Tools\Sessions\GetSessionTool;
use App\Mcp\Tools\Sessions\ListSessionsTool;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SessionToolsTest extends TestCase
{
    private array $sessionFixture = [
        'id' => 'sess_1',
        'campaign_id' => 'camp_1',
        'type' => 'audioUpload',
        'title' => 'The Lost Mines',
        'summary' => 'Party explored...',
        'session_date' => '2024-01-20T19:00:00Z',
        'public' => false,
        'created_at' => '2024-01-20T22:30:00Z',
    ];

    public function test_list_sessions_requires_campaign_id(): void
    {
        ArchivistServer::tool(ListSessionsTool::class)
            ->assertHasErrors()
            ->assertSee('campaign_id');
    }

    public function test_list_sessions_returns_structured_data(): void
    {
        Http::fake([
            '*/v1/sessions*' => Http::response([
                'data' => [$this->sessionFixture], 'total' => 1, 'page' => 1, 'size' => 20, 'pages' => 1,
            ]),
        ]);

        ArchivistServer::tool(ListSessionsTool::class, ['campaign_id' => 'camp_1'])
            ->assertOk()
            ->assertSee('The Lost Mines');
    }

    public function test_get_session_requires_session_id(): void
    {
        ArchivistServer::tool(GetSessionTool::class)
            ->assertHasErrors()
            ->assertSee('session_id');
    }

    public function test_get_session_returns_session_by_id(): void
    {
        Http::fake([
            '*/v1/sessions/sess_1' => Http::response($this->sessionFixture),
        ]);

        ArchivistServer::tool(GetSessionTool::class, ['session_id' => 'sess_1'])
            ->assertOk()
            ->assertSee('sess_1');
    }

    public function test_get_cast_analysis_requires_session_id(): void
    {
        ArchivistServer::tool(GetSessionCastAnalysisTool::class)
            ->assertHasErrors()
            ->assertSee('session_id');
    }

    public function test_get_cast_analysis_returns_data(): void
    {
        Http::fake([
            '*/v1/sessions/sess_1/cast-analysis' => Http::response([
                'id' => 'cast_1',
                'session_id' => 'sess_1',
                'analysis' => ['talkShare' => ['GM' => 0.5]],
                'created_at' => '2024-01-20T22:30:00Z',
                'updated_at' => '2024-01-20T22:30:00Z',
            ]),
        ]);

        ArchivistServer::tool(GetSessionCastAnalysisTool::class, ['session_id' => 'sess_1'])
            ->assertOk()
            ->assertSee('sess_1');
    }
}
