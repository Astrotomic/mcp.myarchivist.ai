<?php

namespace Tests\Gummibeer\Mcp\Tools\Moments;

use App\Data\MomentData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Moments\GetMomentTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Gummibeer\GummibeerTestCase;

final class GetMomentToolGummibeerTest extends GummibeerTestCase
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
        ArchivistServer::tool(GetMomentTool::class, array_merge($query, [
            'moment_id' => 'butlp7odsnxj02l6lwc5wtvr',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertJsonSchema(MomentData::class)
                    ->where('campaign_id', 'cmj78gm6k000004jrvzm7gcjr')
                    ->where('id', 'butlp7odsnxj02l6lwc5wtvr');
            });
    }
}
