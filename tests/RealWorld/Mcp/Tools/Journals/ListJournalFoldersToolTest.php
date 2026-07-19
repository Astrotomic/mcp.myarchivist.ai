<?php

namespace Tests\RealWorld\Mcp\Tools\Journals;

use App\Data\JournalFolderData;
use App\Mcp\Tools\Journals\ListJournalFoldersTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class ListJournalFoldersToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId): void
    {
        $this->assertToolReturnsPaginatedData(
            ListJournalFoldersTool::class,
            array_merge($query, ['campaign_id' => $campaignId]),
            JournalFolderData::class,
            ['campaign_id' => $campaignId],
        );
    }
}
