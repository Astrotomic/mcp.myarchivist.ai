<?php

namespace Tests\Gummibeer\Mcp\Tools\Links;

use App\Data\LinkData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Links\ListLinksTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Gummibeer\GummibeerTestCase;

final class ListLinksToolGummibeerTest extends GummibeerTestCase
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[]],
            'size' => [['size' => 100]],
            'page' => [['page' => 2]],
            'from_id' => [['from_id' => 'xpv723vsxdr7pm1o9w0b8h8f']],
            'from_type' => [['from_type' => 'Location']],
            'to_id' => [['from_id' => 'pbd51edatctika2hzpr8dxss']],
            'to_type' => [['from_type' => 'Location']],
            'alias' => [['alias' => 'Taverne']],
        ];
    }

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query): void
    {
        ArchivistServer::tool(ListLinksTool::class, array_merge($query, [
            'campaign_id' => 'cmj78gm6k000004jrvzm7gcjr',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertPaginatedList(function (AssertableJson $item): void {
                        $item
                            ->assertJsonSchema(LinkData::class)
                            ->where('campaign_id', 'cmj78gm6k000004jrvzm7gcjr');
                    });
            });
    }
}
