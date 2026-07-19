<?php

namespace Tests\Archivist\Mcp\Tools\Items;

use Tests\RealWorld\Mcp\Tools\Items\ListItemsToolTest as RealWorldListItemsToolTest;

final class ListItemsToolTest extends RealWorldListItemsToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmr9ilyjy00020ahu90yhvzx6';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['page' => 2], $campaignId],
            'search' => [['search' => 'Letterbox'], $campaignId],
        ];
    }
}
