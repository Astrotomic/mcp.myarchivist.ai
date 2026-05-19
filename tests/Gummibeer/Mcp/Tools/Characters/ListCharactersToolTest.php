<?php

namespace Tests\Gummibeer\Mcp\Tools\Characters;

use App\Data\CharacterDataShort;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Characters\ListCharactersTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Gummibeer\TestCase;

final class ListCharactersToolTest extends TestCase
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[]],
            'size' => [['size' => 100]],
            'page' => [['size' => 2]],
            'search' => [['search' => 'Flint']],
            'PCs' => [['character_type' => 'PC']],
            'NPCs' => [['character_type' => 'NPC']],
            'approved_only' => [['approved_only' => true]],
        ];
    }

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query): void
    {
        ArchivistServer::tool(ListCharactersTool::class, array_merge($query, [
            'campaign_id' => 'cmj78gm6k000004jrvzm7gcjr',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertPaginatedList(function (AssertableJson $item): void {
                        $item
                            ->assertJsonSchema(CharacterDataShort::class)
                            ->where('campaign_id', 'cmj78gm6k000004jrvzm7gcjr');
                    });
            });
    }
}
