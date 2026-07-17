<?php

namespace Tests\Gummibeer\Mcp\Tools\Links;

use Tests\RealWorld\Mcp\Tools\Links\ListLinksToolTest as RealWorldListLinksToolTest;

final class ListLinksToolTest extends RealWorldListLinksToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmj78gm6k000004jrvzm7gcjr';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['page' => 2], $campaignId],
            'from_id' => [['from_id' => 'xpv723vsxdr7pm1o9w0b8h8f'], $campaignId],
            'from_type' => [['from_type' => 'Location'], $campaignId],
            'to_id' => [['to_id' => 'pbd51edatctika2hzpr8dxss'], $campaignId],
            'to_type' => [['to_type' => 'Location'], $campaignId],
            'alias' => [['alias' => 'Taverne'], $campaignId],
        ];
    }
}
