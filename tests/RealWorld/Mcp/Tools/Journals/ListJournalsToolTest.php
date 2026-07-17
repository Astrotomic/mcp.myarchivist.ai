<?php

namespace Tests\RealWorld\Mcp\Tools\Journals;

use App\Data\JournalDataShort;
use App\Mcp\Tools\Journals\ListJournalsTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class ListJournalsToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId): void
    {
        $this->assertToolReturnsPaginatedData(
            ListJournalsTool::class,
            array_merge($query, ['campaign_id' => $campaignId]),
            JournalDataShort::class,
            ['campaign_id' => $campaignId],
        );
    }
}
