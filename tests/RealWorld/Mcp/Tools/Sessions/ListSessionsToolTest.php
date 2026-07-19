<?php

namespace Tests\RealWorld\Mcp\Tools\Sessions;

use App\Data\SessionDataShort;
use App\Mcp\Tools\Sessions\ListSessionsTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class ListSessionsToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId): void
    {
        $this->assertToolReturnsPaginatedData(
            ListSessionsTool::class,
            array_merge($query, ['campaign_id' => $campaignId]),
            SessionDataShort::class,
            ['campaign_id' => $campaignId],
        );
    }
}
