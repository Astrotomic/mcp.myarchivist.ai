<?php

namespace Tests\Gummibeer\Mcp\Tools\Journals;

use Tests\RealWorld\Mcp\Tools\Journals\GetJournalFolderToolTest as RealWorldGetJournalFolderToolTest;

final class GetJournalFolderToolTest extends RealWorldGetJournalFolderToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmpd8ipzr000004kz41qggzmv'],
        ];
    }
}
