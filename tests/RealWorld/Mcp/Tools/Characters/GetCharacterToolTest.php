<?php

namespace Tests\RealWorld\Mcp\Tools\Characters;

use App\Data\CharacterData;
use App\Mcp\Tools\Characters\GetCharacterTool;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RealWorldTestCase;

abstract class GetCharacterToolTest extends RealWorldTestCase
{
    abstract public static function queryDataProvider(): array;

    #[Test]
    #[DataProvider('queryDataProvider')]
    public function it_fetches_data(array $query, string $campaignId, string $characterId): void
    {
        $this->assertToolReturnsData(
            GetCharacterTool::class,
            array_merge($query, ['character_id' => $characterId]),
            CharacterData::class,
            ['campaign_id' => $campaignId, 'id' => $characterId],
        );
    }
}
