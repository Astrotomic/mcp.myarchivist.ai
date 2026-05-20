<?php

namespace Tests\Gummibeer\Mcp\Tools\Quests;

use App\Data\QuestData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Quests\GetQuestTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\GummibeerTestCase;

final class GetQuestToolTest extends GummibeerTestCase
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
        ArchivistServer::tool(GetQuestTool::class, array_merge($query, [
            'quest_id' => '65d76790-15bb-4bb1-91bf-19d9d80d31b3',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertJsonSchema(QuestData::class)
                    ->where('campaign_id', 'cmj78gm6k000004jrvzm7gcjr')
                    ->where('id', '65d76790-15bb-4bb1-91bf-19d9d80d31b3');

                $this->assertMatchesJsonSnapshot($json);
            });
    }
}
