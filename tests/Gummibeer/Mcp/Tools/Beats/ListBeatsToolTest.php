<?php

namespace Tests\Gummibeer\Mcp\Tools\Beats;

use Tests\RealWorld\Mcp\Tools\Beats\ListBeatsToolTest as RealWorldListBeatsToolTest;

final class ListBeatsToolTest extends RealWorldListBeatsToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmj78gm6k000004jrvzm7gcjr';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['page' => 2], $campaignId],
        ];
    }
}
