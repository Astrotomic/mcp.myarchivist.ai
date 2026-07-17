<?php

namespace Tests\Archivist\Mcp\Tools\Journals;

use Tests\RealWorld\Mcp\Tools\Journals\ListJournalFoldersToolTest as RealWorldListJournalFoldersToolTest;

final class ListJournalFoldersToolTest extends RealWorldListJournalFoldersToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmr9ilyjy00020ahu90yhvzx6'],
        ];
    }
}
