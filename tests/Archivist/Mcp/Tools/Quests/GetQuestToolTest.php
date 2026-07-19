<?php

namespace Tests\Archivist\Mcp\Tools\Quests;

use Tests\RealWorld\Mcp\Tools\Quests\GetQuestToolTest as RealWorldGetQuestToolTest;

final class GetQuestToolTest extends RealWorldGetQuestToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'missing' => [[], 'cmr9ilyjy00020ahu90yhvzx6', 'missing-quest', false],
        ];
    }
}
