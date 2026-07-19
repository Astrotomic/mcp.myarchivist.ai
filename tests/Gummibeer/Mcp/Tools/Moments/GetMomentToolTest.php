<?php

namespace Tests\Gummibeer\Mcp\Tools\Moments;

use Tests\RealWorld\Mcp\Tools\Moments\GetMomentToolTest as RealWorldGetMomentToolTest;

final class GetMomentToolTest extends RealWorldGetMomentToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmj78gm6k000004jrvzm7gcjr', 'butlp7odsnxj02l6lwc5wtvr'],
        ];
    }
}
