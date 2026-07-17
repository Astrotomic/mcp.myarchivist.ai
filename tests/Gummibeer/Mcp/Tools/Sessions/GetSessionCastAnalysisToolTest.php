<?php

namespace Tests\Gummibeer\Mcp\Tools\Sessions;

use Tests\RealWorld\Mcp\Tools\Sessions\GetSessionCastAnalysisToolTest as RealWorldGetSessionCastAnalysisToolTest;

final class GetSessionCastAnalysisToolTest extends RealWorldGetSessionCastAnalysisToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmnhoa5e6000004juew4mv3o2'],
        ];
    }
}
