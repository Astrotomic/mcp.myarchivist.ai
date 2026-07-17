<?php

namespace Tests\Gummibeer\Mcp\Tools\Journals;

use Tests\RealWorld\Mcp\Tools\Journals\GetJournalToolTest as RealWorldGetJournalToolTest;

final class GetJournalToolTest extends RealWorldGetJournalToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmpd8iwks000004ju8zgs9x51'],
        ];
    }
}
