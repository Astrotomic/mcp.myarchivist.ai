<?php

namespace Tests\Gummibeer\Mcp\Tools\Factions;

use Tests\RealWorld\Mcp\Tools\Factions\ListFactionsToolTest as RealWorldListFactionsToolTest;

final class ListFactionsToolTest extends RealWorldListFactionsToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmj78gm6k000004jrvzm7gcjr';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['page' => 2], $campaignId],
            'search' => [['search' => 'Stadtwache'], $campaignId],
        ];
    }
}
