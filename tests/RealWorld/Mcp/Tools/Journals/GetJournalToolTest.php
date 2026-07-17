<?php

namespace Tests\RealWorld\Mcp\Tools\Journals;

use App\Data\JournalData;
use App\Mcp\Tools\Journals\GetJournalTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetJournalToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $entryId, bool $exists = true): void
    {
        if (! $exists) {
            $this->assertToolReturnsErrors(
                GetJournalTool::class,
                array_merge($query, ['entry_id' => $entryId]),
            );

            return;
        }

        $this->assertToolReturnsData(
            GetJournalTool::class,
            array_merge($query, ['entry_id' => $entryId]),
            JournalData::class,
            ['id' => $entryId],
        );
    }
}
