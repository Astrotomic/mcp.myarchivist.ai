<?php

namespace Tests\RealWorld\Mcp\Tools\Factions;

use App\Data\FactionData;
use App\Mcp\Tools\Factions\ListFactionsTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class ListFactionsToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId): void
    {
        $this->assertToolReturnsPaginatedData(
            ListFactionsTool::class,
            array_merge($query, ['campaign_id' => $campaignId]),
            FactionData::class,
            ['campaign_id' => $campaignId],
        );
    }
}
