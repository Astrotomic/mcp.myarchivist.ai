<?php

namespace Tests\Unit\Data;

use App\Data\CharacterData;
use PHPUnit\Framework\Attributes\Test;
use Tests\UnitTestCase;

final class CharacterDataTest extends UnitTestCase
{
    #[Test]
    public function it_accepts_character_alias_as_a_string(): void
    {
        $character = new CharacterData([
            'id' => 'character-id',
            'campaign_id' => 'campaign-id',
            'character_name' => 'Flint Fireforge',
            'character_alias' => 'Nickname',
            'character_aliases' => ['Alias 1'],
            'merge' => false,
            'created_at' => '2026-01-01T00:00:00Z',
        ]);

        $this->assertSame('Nickname', $character->get('character_alias'));
    }
}
