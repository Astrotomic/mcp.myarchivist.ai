<?php

namespace Tests\Archivist\Mcp\Tools\Beats;

use Tests\RealWorld\Mcp\Tools\Beats\ListBeatsToolTest as RealWorldListBeatsToolTest;

final class ListBeatsToolTest extends RealWorldListBeatsToolTest
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
