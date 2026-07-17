<?php

namespace Tests\Archivist\Mcp\Tools\Quests;

use Tests\RealWorld\Mcp\Tools\Quests\ListQuestsToolTest as RealWorldListQuestsToolTest;

final class ListQuestsToolTest extends RealWorldListQuestsToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmr9ilyjy00020ahu90yhvzx6';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['page' => 2], $campaignId],
        ];
    }
}
