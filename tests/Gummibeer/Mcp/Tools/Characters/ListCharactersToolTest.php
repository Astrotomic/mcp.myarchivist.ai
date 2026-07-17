<?php

namespace Tests\Gummibeer\Mcp\Tools\Characters;

use Tests\RealWorld\Mcp\Tools\Characters\ListCharactersToolTest as RealWorldListCharactersToolTest;

final class ListCharactersToolTest extends RealWorldListCharactersToolTest
{
    public static function queryDataProvider(): array
    {
        $campaignId = 'cmj78gm6k000004jrvzm7gcjr';

        return [
            'no query' => [[], $campaignId],
            'size' => [['size' => 100], $campaignId],
            'page' => [['size' => 2], $campaignId],
            'search' => [['search' => 'Flint'], $campaignId],
            'PCs' => [['character_type' => 'PC'], $campaignId],
            'NPCs' => [['character_type' => 'NPC'], $campaignId],
            'approved_only' => [['approved_only' => true], $campaignId],
        ];
    }
}
