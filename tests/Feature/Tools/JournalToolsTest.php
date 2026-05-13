<?php

namespace Tests\Feature\Tools;

use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Journals\GetJournalFolderTool;
use App\Mcp\Tools\Journals\GetJournalTool;
use App\Mcp\Tools\Journals\ListJournalFoldersTool;
use App\Mcp\Tools\Journals\ListJournalsTool;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class JournalToolsTest extends TestCase
{
    private array $journalFixture = [
        'id' => 'journal_1',
        'world_id' => 'camp_1',
        'title' => 'World History',
        'summary' => 'An overview...',
        'content' => 'In the beginning...',
        'content_rich' => null,
        'content_metadata' => null,
        'tags' => ['history'],
        'token_count' => 100,
        'is_public' => false,
        'is_pinned' => null,
        'status' => 'draft',
        'folder_id' => null,
        'cover_image' => null,
        'permission_level' => 'manage',
        'published_at' => null,
        'archived_at' => null,
        'created_at' => '2024-01-01T00:00:00Z',
        'updated_at' => '2024-01-01T00:00:00Z',
    ];

    private array $folderFixture = [
        'id' => 'folder_1',
        'world_id' => 'camp_1',
        'parent_id' => null,
        'name' => 'Lore',
        'path' => 'lore',
        'description' => null,
        'position' => 0,
        'metadata' => null,
        'created_at' => '2024-01-01T00:00:00Z',
        'updated_at' => '2024-01-01T00:00:00Z',
    ];

    public function test_list_journals_requires_campaign_id(): void
    {
        ArchivistServer::tool(ListJournalsTool::class)
            ->assertHasErrors()
            ->assertSee('campaign_id');
    }

    public function test_list_journals_returns_structured_data(): void
    {
        Http::fake([
            '*/v1/journals*' => Http::response([
                'data' => [$this->journalFixture], 'total' => 1,
            ]),
        ]);

        ArchivistServer::tool(ListJournalsTool::class, ['campaign_id' => 'camp_1'])
            ->assertOk()
            ->assertSee('World History');
    }

    public function test_get_journal_requires_entry_id(): void
    {
        ArchivistServer::tool(GetJournalTool::class)
            ->assertHasErrors()
            ->assertSee('entry_id');
    }

    public function test_get_journal_returns_entry_by_id(): void
    {
        Http::fake([
            '*/v1/journals/journal_1' => Http::response($this->journalFixture),
        ]);

        ArchivistServer::tool(GetJournalTool::class, ['entry_id' => 'journal_1'])
            ->assertOk()
            ->assertSee('journal_1');
    }

    public function test_list_journal_folders_requires_campaign_id(): void
    {
        ArchivistServer::tool(ListJournalFoldersTool::class)
            ->assertHasErrors()
            ->assertSee('campaign_id');
    }

    public function test_list_journal_folders_returns_structured_data(): void
    {
        Http::fake([
            '*/v1/journal-folders*' => Http::response([
                'data' => [$this->folderFixture],
            ]),
        ]);

        ArchivistServer::tool(ListJournalFoldersTool::class, ['campaign_id' => 'camp_1'])
            ->assertOk()
            ->assertSee('Lore');
    }

    public function test_get_journal_folder_requires_folder_id(): void
    {
        ArchivistServer::tool(GetJournalFolderTool::class)
            ->assertHasErrors()
            ->assertSee('folder_id');
    }

    public function test_get_journal_folder_returns_folder_by_id(): void
    {
        Http::fake([
            '*/v1/journal-folders/folder_1' => Http::response($this->folderFixture),
        ]);

        ArchivistServer::tool(GetJournalFolderTool::class, ['folder_id' => 'folder_1'])
            ->assertOk()
            ->assertSee('folder_1');
    }
}
