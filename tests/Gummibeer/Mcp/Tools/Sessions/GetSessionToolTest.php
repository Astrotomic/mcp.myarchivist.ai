<?php

namespace Tests\Gummibeer\Mcp\Tools\Sessions;

use App\Data\SessionData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Sessions\GetSessionTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Gummibeer\TestCase;

final class GetSessionToolTest extends TestCase
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
        ArchivistServer::tool(GetSessionTool::class, array_merge($query, [
            'session_id' => 'cmnhoa5e6000004juew4mv3o2',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertJsonSchema(SessionData::class)
                    ->where('campaign_id', 'cmj78gm6k000004jrvzm7gcjr')
                    ->where('id', 'cmnhoa5e6000004juew4mv3o2');
            });
    }
}
