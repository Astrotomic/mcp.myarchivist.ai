<?php

namespace Tests\Gummibeer\Mcp\Tools\Items;

use Tests\RealWorld\Mcp\Tools\Items\GetItemToolTest as RealWorldGetItemToolTest;

final class GetItemToolTest extends RealWorldGetItemToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmj78gm6k000004jrvzm7gcjr', 'xam1273mgbscpk1wt8kxoywj'],
        ];
    }
}
