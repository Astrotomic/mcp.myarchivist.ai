<?php

namespace Tests\Gummibeer\Mcp\Tools\Quests;

use App\Data\QuestDataShort;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Quests\ListQuestsTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\GummibeerTestCase;

final class ListQuestsToolTest extends GummibeerTestCase
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[]],
            'size' => [['size' => 100]],
            'page' => [['page' => 2]],
        ];
    }

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query): void
    {
        ArchivistServer::tool(ListQuestsTool::class, array_merge($query, [
            'campaign_id' => 'cmj78gm6k000004jrvzm7gcjr',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertPaginatedList(function (AssertableJson $item): void {
                        $item
                            ->assertJsonSchema(QuestDataShort::class)
                            ->where('campaign_id', 'cmj78gm6k000004jrvzm7gcjr');
                    });

                $this->assertMatchesJsonSnapshot($json);
            });
    }
}
