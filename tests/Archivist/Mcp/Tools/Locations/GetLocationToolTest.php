<?php

namespace Tests\Archivist\Mcp\Tools\Locations;

use Tests\RealWorld\Mcp\Tools\Locations\GetLocationToolTest as RealWorldGetLocationToolTest;

final class GetLocationToolTest extends RealWorldGetLocationToolTest
{
    public static function queryDataProvider(): array
    {
        return [
            'no query' => [[], 'cmr9ilyjy00020ahu90yhvzx6', 'oi25iioyknchcyrp93j9m6a8'],
        ];
    }
}
