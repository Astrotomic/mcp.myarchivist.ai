<?php

namespace Tests\Unit\Data;

use App\Data\SessionDataShort;
use PHPUnit\Framework\Attributes\Test;
use Tests\UnitTestCase;

final class SessionDataShortTest extends UnitTestCase
{
    #[Test]
    public function it_accepts_all_api_session_types(): void
    {
        foreach (['audioUpload', 'playByPost', 'discordVoice', 'txtUpload', 'rawNotes', 'other'] as $type) {
            $session = new SessionDataShort([
                'id' => 'session-id',
                'campaign_id' => 'campaign-id',
                'type' => $type,
                'public' => false,
                'created_at' => '2026-01-01T00:00:00Z',
            ]);

            $this->assertSame($type, $session->get('type'));
        }
    }
}
