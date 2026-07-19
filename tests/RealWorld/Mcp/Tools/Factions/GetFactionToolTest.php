<?php

namespace Tests\RealWorld\Mcp\Tools\Factions;

use App\Data\FactionData;
use App\Mcp\Tools\Factions\GetFactionTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetFactionToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId, string $factionId): void
    {
        $this->assertToolReturnsData(
            GetFactionTool::class,
            array_merge($query, ['faction_id' => $factionId]),
            FactionData::class,
            ['campaign_id' => $campaignId, 'id' => $factionId],
        );
    }
}
