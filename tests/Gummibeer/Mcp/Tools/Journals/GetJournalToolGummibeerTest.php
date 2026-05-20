<?php

namespace Tests\Gummibeer\Mcp\Tools\Journals;

use App\Data\JournalData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Journals\GetJournalTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\GummibeerTestCase;

final class GetJournalToolGummibeerTest extends GummibeerTestCase
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
        ArchivistServer::tool(GetJournalTool::class, array_merge($query, [
            'entry_id' => 'cmpd8iwks000004ju8zgs9x51',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertJsonSchema(JournalData::class)
                    ->where('id', 'cmpd8iwks000004ju8zgs9x51');
            });
    }
}
