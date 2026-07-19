<?php

namespace Tests\Archivist\Mcp\Tools\Sessions;

use Tests\RealWorld\Mcp\Tools\Sessions\GetSessionTranscriptToolTest as RealWorldGetSessionTranscriptToolTest;

final class GetSessionTranscriptToolTest extends RealWorldGetSessionTranscriptToolTest
{
    public static function sessionDataProvider(): array
    {
        return [
            'session' => ['cmrb7mrzx00000akpsytd8b0f'],
        ];
    }
}
