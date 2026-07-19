<?php

namespace Tests\RealWorld\Mcp\Tools\Locations;

use App\Data\LocationData;
use App\Mcp\Tools\Locations\GetLocationTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetLocationToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId, string $locationId): void
    {
        $this->assertToolReturnsData(
            GetLocationTool::class,
            array_merge($query, ['location_id' => $locationId]),
            LocationData::class,
            ['campaign_id' => $campaignId, 'id' => $locationId],
        );
    }
}
