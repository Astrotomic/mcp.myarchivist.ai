<?php

namespace Tests\Archivist\Mcp\Tools\Sessions;

use Tests\RealWorld\Mcp\Tools\Sessions\GetSessionCastAnalysisToolTest as RealWorldGetSessionCastAnalysisToolTest;

final class GetSessionCastAnalysisToolTest extends RealWorldGetSessionCastAnalysisToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'missing' => [[], 'cmrb7mrzx00000akpsytd8b0f', false],
        ];
    }
}
