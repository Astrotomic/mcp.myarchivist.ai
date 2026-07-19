<?php

namespace Tests\Gummibeer\Mcp\Tools\Sessions;

use Tests\RealWorld\Mcp\Tools\Sessions\ListSessionsToolTest as RealWorldListSessionsToolTest;

final class ListSessionsToolTest extends RealWorldListSessionsToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmj78gm6k000004jrvzm7gcjr';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['page' => 2], $campaignId],
            'session_type' => [['session_type' => 'rawNotes'], $campaignId],
            'public_only' => [['public_only' => false], $campaignId],
        ];
    }
}
