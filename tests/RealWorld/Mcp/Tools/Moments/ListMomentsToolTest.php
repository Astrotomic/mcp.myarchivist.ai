<?php

namespace Tests\RealWorld\Mcp\Tools\Moments;

use App\Data\MomentData;
use App\Mcp\Tools\Moments\ListMomentsTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class ListMomentsToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId): void
    {
        $this->assertToolReturnsPaginatedData(
            ListMomentsTool::class,
            array_merge($query, ['campaign_id' => $campaignId]),
            MomentData::class,
            ['campaign_id' => $campaignId],
        );
    }
}
