<?php

namespace Tests\Archivist\Mcp\Tools\Factions;

use Tests\RealWorld\Mcp\Tools\Factions\ListFactionsToolTest as RealWorldListFactionsToolTest;

final class ListFactionsToolTest extends RealWorldListFactionsToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmr9ilyjy00020ahu90yhvzx6';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['page' => 2], $campaignId],
            'search' => [['search' => 'Tower Guards'], $campaignId],
        ];
    }
}
