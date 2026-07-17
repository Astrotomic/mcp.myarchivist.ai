<?php

namespace Tests\Archivist\Mcp\Tools\Sessions;

use Tests\RealWorld\Mcp\Tools\Sessions\GetSessionHandoutToolTest as RealWorldGetSessionHandoutToolTest;

final class GetSessionHandoutToolTest extends RealWorldGetSessionHandoutToolTest
{
    public static function sessionDataProvider(): array
    {
        return [
            'missing' => ['cmrb7mrzx00000akpsytd8b0f', false],
        ];
    }
}
