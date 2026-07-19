<?php

namespace Tests\Gummibeer\Mcp\Tools\Factions;

use Tests\RealWorld\Mcp\Tools\Factions\GetFactionToolTest as RealWorldGetFactionToolTest;

final class GetFactionToolTest extends RealWorldGetFactionToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmj78gm6k000004jrvzm7gcjr', 'r86ej50cbqrfissek140wtyh'],
        ];
    }
}
