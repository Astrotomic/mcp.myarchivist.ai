<?php

namespace Tests\RealWorld\Mcp\Tools\Sessions;

use App\Data\SessionTranscriptData;
use App\Mcp\Tools\Sessions\GetSessionTranscriptTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetSessionTranscriptToolTest extends RealWorldTestCase
{
    abstract public static function sessionDataProvider(): array;

    #[Test]
    #[DataProvider('sessionDataProvider')]
    public function it_fetches_data(string $sessionId): void
    {
        $this->assertToolReturnsData(
            GetSessionTranscriptTool::class,
            ['session_id' => $sessionId],
            SessionTranscriptData::class,
            snapshot: false,
        );
    }

    protected function skipHttpFixtures(): bool
    {
        return true;
    }
}
