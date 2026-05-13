<?php

namespace Tests\Feature\Tools;

use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Quests\GetQuestTool;
use App\Mcp\Tools\Quests\ListQuestsTool;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class QuestToolsTest extends TestCase
{
    private array $questListFixture = [
        'id' => 'quest_1',
        'campaign_id' => 'camp_1',
        'order_index' => 1,
        'quest_name' => 'Recover the Azure Sigil',
        'quest_giver' => 'Aria Voss',
        'quest_giver_id' => 'char_1',
        'quest_category' => 'main',
        'status' => 'in-progress',
        'next_action' => 'Question the ferryman.',
        'resolution' => null,
        'objective_count' => 3,
        'completed_objective_count' => 1,
        'progress_entry_count' => 2,
        'related_entity_count' => 3,
        'first_session' => null,
        'last_session' => null,
        'created_at' => '2024-01-01T00:00:00Z',
        'updated_at' => '2024-01-02T00:00:00Z',
    ];

    public function test_list_quests_requires_campaign_id(): void
    {
        ArchivistServer::tool(ListQuestsTool::class)
            ->assertHasErrors()
            ->assertSee('campaign_id');
    }

    public function test_list_quests_returns_structured_data(): void
    {
        Http::fake([
            '*/v1/quests*' => Http::response([
                'data' => [$this->questListFixture], 'total' => 1, 'page' => 1, 'size' => 20, 'pages' => 1,
            ]),
        ]);

        ArchivistServer::tool(ListQuestsTool::class, ['campaign_id' => 'camp_1'])
            ->assertOk()
            ->assertSee('Azure Sigil');
    }

    public function test_list_quests_validates_status_enum(): void
    {
        ArchivistServer::tool(ListQuestsTool::class, ['campaign_id' => 'camp_1', 'status' => 'invalid-status'])
            ->assertHasErrors()
            ->assertSee('Status must be one of');
    }

    public function test_list_quests_validates_quest_category_enum(): void
    {
        ArchivistServer::tool(ListQuestsTool::class, ['campaign_id' => 'camp_1', 'quest_category' => 'bogus'])
            ->assertHasErrors()
            ->assertSee('Quest category must be one of');
    }

    public function test_get_quest_requires_quest_id(): void
    {
        ArchivistServer::tool(GetQuestTool::class)
            ->assertHasErrors()
            ->assertSee('quest_id');
    }

    public function test_get_quest_returns_full_quest_by_id(): void
    {
        Http::fake([
            '*/v1/quests/quest_1' => Http::response(array_merge($this->questListFixture, [
                'success_definition' => 'Return the sigil.',
                'failure_conditions' => null,
                'objectives' => [],
                'progress_log' => [],
                'progress_log_entries' => [],
                'related_characters' => [],
                'related_factions' => [],
                'related_locations' => [],
                'related_items' => [],
                'related_entity_refs' => [],
            ])),
        ]);

        ArchivistServer::tool(GetQuestTool::class, ['quest_id' => 'quest_1'])
            ->assertOk()
            ->assertSee('quest_1');
    }
}
