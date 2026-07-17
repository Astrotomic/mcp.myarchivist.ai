<?php

namespace Tests\RealWorld\Mcp\Tools\Beats;

use App\Data\BeatData;
use App\Mcp\Tools\Beats\ListBeatsTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class ListBeatsToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId): void
    {
        $this->assertToolReturnsPaginatedData(
            ListBeatsTool::class,
            array_merge($query, ['campaign_id' => $campaignId]),
            BeatData::class,
            ['campaign_id' => $campaignId],
        );
    }
}
