<?php

namespace Tests\RealWorld\Mcp\Tools\Sessions;

use App\Data\SessionData;
use App\Mcp\Tools\Sessions\GetSessionTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetSessionToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId, string $sessionId): void
    {
        $this->assertToolReturnsData(
            GetSessionTool::class,
            array_merge($query, ['session_id' => $sessionId]),
            SessionData::class,
            ['campaign_id' => $campaignId, 'id' => $sessionId],
        );
    }
}
