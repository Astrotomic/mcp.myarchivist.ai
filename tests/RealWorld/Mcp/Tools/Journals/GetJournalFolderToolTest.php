<?php

namespace Tests\RealWorld\Mcp\Tools\Journals;

use App\Data\JournalFolderData;
use App\Mcp\Tools\Journals\GetJournalFolderTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetJournalFolderToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $folderId, bool $exists = true): void
    {
        if (! $exists) {
            $this->assertToolReturnsErrors(
                GetJournalFolderTool::class,
                array_merge($query, ['folder_id' => $folderId]),
            );

            return;
        }

        $this->assertToolReturnsData(
            GetJournalFolderTool::class,
            array_merge($query, ['folder_id' => $folderId]),
            JournalFolderData::class,
            ['id' => $folderId],
        );
    }
}
