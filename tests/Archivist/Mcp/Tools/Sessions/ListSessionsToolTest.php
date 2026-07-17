<?php

namespace Tests\Archivist\Mcp\Tools\Sessions;

use Tests\RealWorld\Mcp\Tools\Sessions\ListSessionsToolTest as RealWorldListSessionsToolTest;

final class ListSessionsToolTest extends RealWorldListSessionsToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmr9ilyjy00020ahu90yhvzx6';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['page' => 2], $campaignId],
            'session_type' => [['session_type' => 'txtUpload'], $campaignId],
            'public_only' => [['public_only' => false], $campaignId],
        ];
    }
}
