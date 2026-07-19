<?php

namespace Tests\RealWorld\Mcp\Tools\Moments;

use App\Data\MomentData;
use App\Mcp\Tools\Moments\GetMomentTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetMomentToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId, string $momentId): void
    {
        $this->assertToolReturnsData(
            GetMomentTool::class,
            array_merge($query, ['moment_id' => $momentId]),
            MomentData::class,
            ['campaign_id' => $campaignId, 'id' => $momentId],
        );
    }
}
