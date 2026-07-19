<?php

namespace Tests\RealWorld\Mcp\Tools\Quests;

use App\Data\QuestData;
use App\Mcp\Tools\Quests\GetQuestTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetQuestToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId, string $questId, bool $exists = true): void
    {
        if (! $exists) {
            $this->assertToolReturnsErrors(
                GetQuestTool::class,
                array_merge($query, ['quest_id' => $questId]),
            );

            return;
        }

        $this->assertToolReturnsData(
            GetQuestTool::class,
            array_merge($query, ['quest_id' => $questId]),
            QuestData::class,
            ['campaign_id' => $campaignId, 'id' => $questId],
        );
    }
}
