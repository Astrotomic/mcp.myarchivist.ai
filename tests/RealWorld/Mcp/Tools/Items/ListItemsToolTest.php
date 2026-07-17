<?php

namespace Tests\RealWorld\Mcp\Tools\Items;

use App\Data\ItemData;
use App\Mcp\Tools\Items\ListItemsTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class ListItemsToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId): void
    {
        $this->assertToolReturnsPaginatedData(
            ListItemsTool::class,
            array_merge($query, ['campaign_id' => $campaignId]),
            ItemData::class,
            ['campaign_id' => $campaignId],
        );
    }
}
