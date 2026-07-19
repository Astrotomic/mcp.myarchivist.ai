<?php

namespace Tests\Archivist\Mcp\Tools\Sessions;

use Tests\RealWorld\Mcp\Tools\Sessions\GetSessionToolTest as RealWorldGetSessionToolTest;

final class GetSessionToolTest extends RealWorldGetSessionToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmr9ilyjy00020ahu90yhvzx6', 'cmrb7mrzx00000akpsytd8b0f'],
        ];
    }
}
