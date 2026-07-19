<?php

namespace Tests\RealWorld\Mcp\Tools\Characters;

use App\Data\CharacterData;
use App\Mcp\Tools\Characters\ListCharactersTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class ListCharactersToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId): void
    {
        $this->assertToolReturnsPaginatedData(
            ListCharactersTool::class,
            array_merge($query, ['campaign_id' => $campaignId]),
            CharacterData::class,
            ['campaign_id' => $campaignId],
        );
    }
}
