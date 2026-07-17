<?php

namespace Tests\Archivist\Mcp\Tools\Beats;

use Tests\RealWorld\Mcp\Tools\Beats\GetBeatToolTest as RealWorldGetBeatToolTest;

final class GetBeatToolTest extends RealWorldGetBeatToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmr9ilyjy00020ahu90yhvzx6', 'cmrb7p2zw00000tbwywasexot'],
        ];
    }
}
