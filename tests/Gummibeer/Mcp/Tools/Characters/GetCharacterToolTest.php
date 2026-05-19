<?php

namespace Tests\Gummibeer\Mcp\Tools\Characters;

use App\Data\CharacterData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Characters\GetCharacterTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Gummibeer\TestCase;

final class GetCharacterToolTest extends TestCase
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
        ArchivistServer::tool(GetCharacterTool::class, array_merge($query, [
            'character_id' => 'ffh55eoknmwl9tseg0i0or9y',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertJsonSchema(CharacterData::class)
                    ->where('campaign_id', 'cmj78gm6k000004jrvzm7gcjr')
                    ->where('id', 'ffh55eoknmwl9tseg0i0or9y');
            });
    }
}
