<?php

namespace Tests\Gummibeer\Mcp\Tools\Sessions;

use Tests\RealWorld\Mcp\Tools\Sessions\GetSessionTranscriptToolTest as RealWorldGetSessionTranscriptToolTest;

final class GetSessionTranscriptToolTest extends RealWorldGetSessionTranscriptToolTest
{
    public static function sessionDataProvider(): array
    {
        return [
            'session' => ['cmp49egl1000204if2fmow69m'],
        ];
    }
}
