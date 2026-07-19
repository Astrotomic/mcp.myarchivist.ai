<?php

namespace Tests\Archivist\Mcp\Tools\Links;

use Tests\RealWorld\Mcp\Tools\Links\ListLinksToolTest as RealWorldListLinksToolTest;

final class ListLinksToolTest extends RealWorldListLinksToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmr9ilyjy00020ahu90yhvzx6';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['page' => 2], $campaignId],
            'from_id' => [['from_id' => $campaignId], $campaignId],
            'from_type' => [['from_type' => 'World'], $campaignId],
            'to_id' => [['to_id' => 'bpma6otgrarkbbwcgjxpy727'], $campaignId],
            'to_type' => [['to_type' => 'Character'], $campaignId],
            'alias' => [['alias' => 'Pepe'], $campaignId],
        ];
    }
}
