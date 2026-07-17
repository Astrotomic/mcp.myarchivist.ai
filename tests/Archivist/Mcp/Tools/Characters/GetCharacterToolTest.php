<?php

namespace Tests\Archivist\Mcp\Tools\Characters;

use Tests\RealWorld\Mcp\Tools\Characters\GetCharacterToolTest as RealWorldGetCharacterToolTest;

final class GetCharacterToolTest extends RealWorldGetCharacterToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmr9ilyjy00020ahu90yhvzx6', 'jyo4bb55tat0qva20e4017pf'],
        ];
    }
}
