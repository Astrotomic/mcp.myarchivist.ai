<?php

namespace Tests\Gummibeer\Mcp\Tools\Sessions;

use App\Data\SessionDataShort;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Sessions\ListSessionsTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\GummibeerTestCase;

final class ListSessionsToolGummibeerTest extends GummibeerTestCase
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[]],
            'size' => [['size' => 100]],
            'page' => [['page' => 2]],
            'session_type' => [['session_type' => 'rawNotes']],
            'public_only' => [['public_only' => false]],
        ];
    }

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query): void
    {
        ArchivistServer::tool(ListSessionsTool::class, array_merge($query, [
            'campaign_id' => 'cmj78gm6k000004jrvzm7gcjr',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertPaginatedList(function (AssertableJson $item): void {
                        $item
                            ->assertJsonSchema(SessionDataShort::class)
                            ->where('campaign_id', 'cmj78gm6k000004jrvzm7gcjr');
                    });
            });
    }
}
