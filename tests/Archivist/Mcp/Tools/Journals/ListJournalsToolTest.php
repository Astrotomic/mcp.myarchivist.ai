<?php

namespace Tests\Archivist\Mcp\Tools\Journals;

use Tests\RealWorld\Mcp\Tools\Journals\ListJournalsToolTest as RealWorldListJournalsToolTest;

final class ListJournalsToolTest extends RealWorldListJournalsToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmr9ilyjy00020ahu90yhvzx6'],
        ];
    }
}
