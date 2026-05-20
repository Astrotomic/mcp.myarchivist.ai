<?php

namespace Tests\Gummibeer\Mcp\Tools\Beats;

use App\Data\BeatData;
use App\Mcp\Servers\ArchivistServer;
use App\Mcp\Tools\Beats\GetBeatTool;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\GummibeerTestCase;

final class GetBeatToolTest extends GummibeerTestCase
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
        ArchivistServer::tool(GetBeatTool::class, array_merge($query, [
            'beat_id' => 'cmmwj6k9o00000i8g38tra5i0',
        ]))
            ->assertOk()
            ->assertStructuredContent(function (AssertableJson $json): void {
                $json
                    ->assertJsonSchema(BeatData::class)
                    ->where('campaign_id', 'cmj78gm6k000004jrvzm7gcjr')
                    ->where('id', 'cmmwj6k9o00000i8g38tra5i0');

                $this->assertMatchesJsonSnapshot($json);
            });
    }
}
