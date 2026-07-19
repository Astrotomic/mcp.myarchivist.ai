<?php

namespace Tests\Archivist\Mcp\Tools\Items;

use Tests\RealWorld\Mcp\Tools\Items\GetItemToolTest as RealWorldGetItemToolTest;

final class GetItemToolTest extends RealWorldGetItemToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmr9ilyjy00020ahu90yhvzx6', 'ww6uap01gkdr5ujdfdv4fyef'],
        ];
    }
}
