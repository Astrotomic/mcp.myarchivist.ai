<?php

namespace Tests\RealWorld\Mcp\Tools\Sessions;

use App\Data\CastAnalysisData;
use App\Mcp\Tools\Sessions\GetSessionCastAnalysisTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetSessionCastAnalysisToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $sessionId, bool $exists = true): void
    {
        if (! $exists) {
            $this->assertToolReturnsErrors(
                GetSessionCastAnalysisTool::class,
                array_merge($query, ['session_id' => $sessionId]),
            );

            return;
        }

        $this->assertToolReturnsData(
            GetSessionCastAnalysisTool::class,
            array_merge($query, ['session_id' => $sessionId]),
            CastAnalysisData::class,
            ['session_id' => $sessionId],
        );
    }
}
