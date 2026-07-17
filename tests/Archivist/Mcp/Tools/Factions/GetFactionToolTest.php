<?php

namespace Tests\Archivist\Mcp\Tools\Factions;

use Tests\RealWorld\Mcp\Tools\Factions\GetFactionToolTest as RealWorldGetFactionToolTest;

final class GetFactionToolTest extends RealWorldGetFactionToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmr9ilyjy00020ahu90yhvzx6', 'zj5f79poaqcq0urv3r9y7rps'],
        ];
    }
}
