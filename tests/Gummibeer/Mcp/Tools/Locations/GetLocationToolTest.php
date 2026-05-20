<?php

namespace Tests\Gummibeer\Mcp\Tools\Locations;

use App\Data\LocationData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Locations\GetLocationTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Gummibeer\TestCase;

final class GetLocationToolTest extends TestCase
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
        ArchivistServer::tool(GetLocationTool::class, array_merge($query, [
            'location_id' => 'xs5x4fz3qp1i6uvcrfrof2w1',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertJsonSchema(LocationData::class)
                    ->where('campaign_id', 'cmj78gm6k000004jrvzm7gcjr')
                    ->where('id', 'xs5x4fz3qp1i6uvcrfrof2w1');
            });
    }
}
