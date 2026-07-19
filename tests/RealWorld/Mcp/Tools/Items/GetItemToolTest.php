<?php

namespace Tests\RealWorld\Mcp\Tools\Items;

use App\Data\ItemData;
use App\Mcp\Tools\Items\GetItemTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetItemToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId, string $itemId): void
    {
        $this->assertToolReturnsData(
            GetItemTool::class,
            array_merge($query, ['item_id' => $itemId]),
            ItemData::class,
            ['campaign_id' => $campaignId, 'id' => $itemId],
        );
    }
}
