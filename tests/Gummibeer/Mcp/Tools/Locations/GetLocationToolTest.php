<?php

namespace Tests\Gummibeer\Mcp\Tools\Locations;

use Tests\RealWorld\Mcp\Tools\Locations\GetLocationToolTest as RealWorldGetLocationToolTest;

final class GetLocationToolTest extends RealWorldGetLocationToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmj78gm6k000004jrvzm7gcjr', 'xs5x4fz3qp1i6uvcrfrof2w1'],
        ];
    }
}
