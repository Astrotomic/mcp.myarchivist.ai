<?php

namespace Tests\RealWorld\Mcp\Tools\Sessions;

use App\Data\SessionHandoutData;
use App\Mcp\Tools\Sessions\GetSessionHandoutTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetSessionHandoutToolTest extends RealWorldTestCase
{
    abstract public static function sessionDataProvider(): array;

    #[Test]
    #[DataProvider('sessionDataProvider')]
    public function it_fetches_data(string $sessionId, bool $exists = true): void
    {
        if (! $exists) {
            $this->assertToolReturnsErrors(
                GetSessionHandoutTool::class,
                ['session_id' => $sessionId],
            );

            return;
        }

        $this->assertToolReturnsData(
            GetSessionHandoutTool::class,
            ['session_id' => $sessionId],
            SessionHandoutData::class,
        );
    }
}
