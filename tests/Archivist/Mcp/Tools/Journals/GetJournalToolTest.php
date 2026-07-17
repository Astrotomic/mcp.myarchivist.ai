<?php

namespace Tests\Archivist\Mcp\Tools\Journals;

use Tests\RealWorld\Mcp\Tools\Journals\GetJournalToolTest as RealWorldGetJournalToolTest;

final class GetJournalToolTest extends RealWorldGetJournalToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'missing' => [[], 'missing-journal', false],
        ];
    }
}
