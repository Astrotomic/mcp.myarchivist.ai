<?php

namespace Tests\RealWorld\Mcp\Tools\Links;

use App\Data\LinkData;
use App\Mcp\Tools\Links\ListLinksTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class ListLinksToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId): void
    {
        $this->assertToolReturnsPaginatedData(
            ListLinksTool::class,
            array_merge($query, ['campaign_id' => $campaignId]),
            LinkData::class,
            ['campaign_id' => $campaignId],
        );
    }
}
