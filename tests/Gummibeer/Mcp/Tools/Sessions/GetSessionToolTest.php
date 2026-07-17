<?php

namespace Tests\Gummibeer\Mcp\Tools\Sessions;

use Tests\RealWorld\Mcp\Tools\Sessions\GetSessionToolTest as RealWorldGetSessionToolTest;

final class GetSessionToolTest extends RealWorldGetSessionToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmj78gm6k000004jrvzm7gcjr', 'cmnhoa5e6000004juew4mv3o2'],
        ];
    }
}
