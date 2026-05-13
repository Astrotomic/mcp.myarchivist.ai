<?php

namespace Tests\Feature\Tools;

use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Characters\GetCharacterTool;
use App\Mcp\Tools\Characters\ListCharactersTool;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CharacterToolsTest extends TestCase
{
    private array $characterFixture = [
        'id' => 'char_1',
        'campaign_id' => 'camp_1',
        'character_name' => 'Thorin',
        'character_aliases' => ['The Bold'],
        'player_name' => null,
        'player' => null,
        'description' => 'A dwarf',
        'backstory' => null,
        'speaker_id' => null,
        'type' => 'PC',
        'approved' => true,
        'created_at' => '2024-01-01T00:00:00Z',
    ];

    public function test_list_characters_requires_campaign_id(): void
    {
        ArchivistServer::tool(ListCharactersTool::class)
            ->assertHasErrors()
            ->assertSee('campaign_id');
    }

    public function test_list_characters_returns_structured_data(): void
    {
        Http::fake([
            '*/v1/characters*' => Http::response([
                'data' => [$this->characterFixture], 'total' => 1, 'page' => 1, 'size' => 20, 'pages' => 1,
            ]),
        ]);

        ArchivistServer::tool(ListCharactersTool::class, ['campaign_id' => 'camp_1'])
            ->assertOk()
            ->assertSee('Thorin');
    }

    public function test_list_characters_returns_error_on_api_failure(): void
    {
        Http::fake([
            '*/v1/characters*' => Http::response(['detail' => 'Unauthorized'], 401),
        ]);

        ArchivistServer::tool(ListCharactersTool::class, ['campaign_id' => 'camp_1'])
            ->assertHasErrors()
            ->assertSee('Unauthorized');
    }

    public function test_get_character_returns_character_by_id(): void
    {
        Http::fake([
            '*/v1/characters/char_1' => Http::response($this->characterFixture),
        ]);

        ArchivistServer::tool(GetCharacterTool::class, ['character_id' => 'char_1'])
            ->assertOk()
            ->assertSee('char_1');
    }

    public function test_get_character_requires_character_id(): void
    {
        ArchivistServer::tool(GetCharacterTool::class)
            ->assertHasErrors()
            ->assertSee('character_id');
    }
}
