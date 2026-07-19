<?php

namespace Tests\Gummibeer\Mcp\Tools\Sessions;

use Tests\RealWorld\Mcp\Tools\Sessions\GetSessionHandoutToolTest as RealWorldGetSessionHandoutToolTest;

final class GetSessionHandoutToolTest extends RealWorldGetSessionHandoutToolTest
{
    public static function sessionDataProvider(): array
    {
        return [
            'session' => ['cmnhoa5e6000004juew4mv3o2'],
        ];
    }
}
