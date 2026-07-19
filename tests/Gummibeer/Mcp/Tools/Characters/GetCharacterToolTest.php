<?php

namespace Tests\Gummibeer\Mcp\Tools\Characters;

use Tests\RealWorld\Mcp\Tools\Characters\GetCharacterToolTest as RealWorldGetCharacterToolTest;

final class GetCharacterToolTest extends RealWorldGetCharacterToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmj78gm6k000004jrvzm7gcjr', 'ffh55eoknmwl9tseg0i0or9y'],
        ];
    }
}
