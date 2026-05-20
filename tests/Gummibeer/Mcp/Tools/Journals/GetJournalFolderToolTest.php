<?php

namespace Tests\Gummibeer\Mcp\Tools\Journals;

use App\Data\JournalFolderData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Journals\GetJournalFolderTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Gummibeer\TestCase;

final class GetJournalFolderToolTest extends TestCase
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[]],
        ];
    }

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query): void
    {
        ArchivistServer::tool(GetJournalFolderTool::class, array_merge($query, [
            'folder_id' => 'cmpd8ipzr000004kz41qggzmv',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertJsonSchema(JournalFolderData::class)
                    ->where('id', 'cmpd8ipzr000004kz41qggzmv');
            });
    }
}
