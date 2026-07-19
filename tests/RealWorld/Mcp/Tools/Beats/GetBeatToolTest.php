<?php

namespace Tests\RealWorld\Mcp\Tools\Beats;

use App\Data\BeatData;
use App\Mcp\Tools\Beats\GetBeatTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetBeatToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId, string $beatId): void
    {
        $this->assertToolReturnsData(
            GetBeatTool::class,
            array_merge($query, ['beat_id' => $beatId]),
            BeatData::class,
            ['campaign_id' => $campaignId, 'id' => $beatId],
        );
    }
}
