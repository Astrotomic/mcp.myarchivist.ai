<?php

namespace Tests\Gummibeer\Mcp\Tools\Items;

use Tests\RealWorld\Mcp\Tools\Items\ListItemsToolTest as RealWorldListItemsToolTest;

final class ListItemsToolTest extends RealWorldListItemsToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmj78gm6k000004jrvzm7gcjr';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['page' => 2], $campaignId],
            'search' => [['search' => 'schwert'], $campaignId],
        ];
    }
}
