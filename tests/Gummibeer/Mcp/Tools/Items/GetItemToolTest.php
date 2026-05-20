<?php

namespace Tests\Gummibeer\Mcp\Tools\Items;

use App\Data\ItemData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Items\GetItemTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\GummibeerTestCase;

final class GetItemToolTest extends GummibeerTestCase
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
        ArchivistServer::tool(GetItemTool::class, array_merge($query, [
            'item_id' => 'xam1273mgbscpk1wt8kxoywj',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertJsonSchema(ItemData::class)
                    ->where('campaign_id', 'cmj78gm6k000004jrvzm7gcjr')
                    ->where('id', 'xam1273mgbscpk1wt8kxoywj');

                $this->assertMatchesJsonSnapshot($json);
            });
    }
}
