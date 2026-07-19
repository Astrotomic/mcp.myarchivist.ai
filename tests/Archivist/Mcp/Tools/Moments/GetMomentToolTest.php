<?php

namespace Tests\Archivist\Mcp\Tools\Moments;

use Tests\RealWorld\Mcp\Tools\Moments\GetMomentToolTest as RealWorldGetMomentToolTest;

final class GetMomentToolTest extends RealWorldGetMomentToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmr9ilyjy00020ahu90yhvzx6', 'dagwuttbaeey5hftkhkhz060'],
        ];
    }
}
