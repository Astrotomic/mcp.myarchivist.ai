<?php

namespace Tests\Archivist\Mcp\Tools\Journals;

use Tests\RealWorld\Mcp\Tools\Journals\GetJournalFolderToolTest as RealWorldGetJournalFolderToolTest;

final class GetJournalFolderToolTest extends RealWorldGetJournalFolderToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'missing' => [[], 'missing-journal-folder', false],
        ];
    }
}
