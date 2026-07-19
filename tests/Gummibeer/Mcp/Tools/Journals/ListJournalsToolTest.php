<?php

namespace Tests\Gummibeer\Mcp\Tools\Journals;

use Tests\RealWorld\Mcp\Tools\Journals\ListJournalsToolTest as RealWorldListJournalsToolTest;

final class ListJournalsToolTest extends RealWorldListJournalsToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmj78gm6k000004jrvzm7gcjr'],
        ];
    }
}
