<?php

namespace Tests\Gummibeer\Mcp\Tools\Beats;

use Tests\RealWorld\Mcp\Tools\Beats\GetBeatToolTest as RealWorldGetBeatToolTest;

final class GetBeatToolTest extends RealWorldGetBeatToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmj78gm6k000004jrvzm7gcjr', 'cmmwj6k9o00000i8g38tra5i0'],
        ];
    }
}
