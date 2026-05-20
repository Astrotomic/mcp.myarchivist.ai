<?php

namespace Tests\Gummibeer\Mcp\Tools\Factions;

use App\Data\FactionData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Factions\GetFactionTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Gummibeer\TestCase;

final class GetFactionToolTest extends TestCase
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
        ArchivistServer::tool(GetFactionTool::class, array_merge($query, [
            'faction_id' => 'r86ej50cbqrfissek140wtyh',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertJsonSchema(FactionData::class)
                    ->where('campaign_id', 'cmj78gm6k000004jrvzm7gcjr')
                    ->where('id', 'r86ej50cbqrfissek140wtyh');
            });
    }
}
