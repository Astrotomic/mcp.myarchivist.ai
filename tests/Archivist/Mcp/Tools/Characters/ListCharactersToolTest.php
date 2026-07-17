<?php

namespace Tests\Archivist\Mcp\Tools\Characters;

use Tests\RealWorld\Mcp\Tools\Characters\ListCharactersToolTest as RealWorldListCharactersToolTest;

final class ListCharactersToolTest extends RealWorldListCharactersToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmr9ilyjy00020ahu90yhvzx6';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['size' => 2], $campaignId],
            'search' => [['search' => 'Bogo'], $campaignId],
            'PCs' => [['character_type' => 'PC'], $campaignId],
            'NPCs' => [['character_type' => 'NPC'], $campaignId],
            'approved_only' => [['approved_only' => true], $campaignId],
        ];
    }
}
