<?php

namespace Tests\Gummibeer\Mcp\Tools\Quests;

use Tests\RealWorld\Mcp\Tools\Quests\GetQuestToolTest as RealWorldGetQuestToolTest;

final class GetQuestToolTest extends RealWorldGetQuestToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmj78gm6k000004jrvzm7gcjr', '65d76790-15bb-4bb1-91bf-19d9d80d31b3'],
        ];
    }
}
