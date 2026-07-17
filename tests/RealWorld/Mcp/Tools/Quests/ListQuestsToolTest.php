<?php

namespace Tests\RealWorld\Mcp\Tools\Quests;

use App\Data\QuestDataShort;
use App\Mcp\Tools\Quests\ListQuestsTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class ListQuestsToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId): void
    {
        $this->assertToolReturnsPaginatedData(
            ListQuestsTool::class,
            array_merge($query, ['campaign_id' => $campaignId]),
            QuestDataShort::class,
            ['campaign_id' => $campaignId],
        );
    }
}
