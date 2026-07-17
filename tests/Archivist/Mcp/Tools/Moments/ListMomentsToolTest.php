<?php

namespace Tests\Archivist\Mcp\Tools\Moments;

use Tests\RealWorld\Mcp\Tools\Moments\ListMomentsToolTest as RealWorldListMomentsToolTest;

final class ListMomentsToolTest extends RealWorldListMomentsToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmr9ilyjy00020ahu90yhvzx6';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['page' => 2], $campaignId],
            'search' => [['search' => 'dragon'], $campaignId],
        ];
    }
}
