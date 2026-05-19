<?php

namespace Tests\Gummibeer\Mcp\Tools\Sessions;

use App\Data\CastAnalysisData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Sessions\GetSessionCastAnalysisTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Gummibeer\TestCase;

final class GetSessionCastAnalysisToolTest extends TestCase
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
        ArchivistServer::tool(GetSessionCastAnalysisTool::class, array_merge($query, [
            'session_id' => 'cmnhoa5e6000004juew4mv3o2',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertJsonSchema(CastAnalysisData::class)
                    ->where('session_id', 'cmnhoa5e6000004juew4mv3o2');
            });
    }
}
