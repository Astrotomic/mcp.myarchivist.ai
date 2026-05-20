<?php

namespace Tests\Gummibeer\Mcp\Tools\Journals;

use App\Data\JournalDataShort;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Journals\ListJournalsTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\GummibeerTestCase;

final class ListJournalsToolTest extends GummibeerTestCase
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
        ArchivistServer::tool(ListJournalsTool::class, array_merge($query, [
            'campaign_id' => 'cmj78gm6k000004jrvzm7gcjr',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertPaginatedList(function (AssertableJson $item): void {
                        $item
                            ->assertJsonSchema(JournalDataShort::class)
                            ->where('campaign_id', 'cmj78gm6k000004jrvzm7gcjr');
                    });
            });
    }
}
