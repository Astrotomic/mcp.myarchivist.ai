<?php

namespace Tests\Gummibeer\Mcp\Tools\Journals;

use Tests\RealWorld\Mcp\Tools\Journals\ListJournalFoldersToolTest as RealWorldListJournalFoldersToolTest;

final class ListJournalFoldersToolTest extends RealWorldListJournalFoldersToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmj78gm6k000004jrvzm7gcjr'],
        ];
    }
}
