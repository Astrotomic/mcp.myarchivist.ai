<?php

namespace Tests\Unit\Data;

use App\Data\JournalData;
use PHPUnit\Framework\Attributes\Test;
use Tests\UnitTestCase;

final class JournalDataTest extends UnitTestCase
{
    #[Test]
    public function it_accepts_content_metadata_as_an_object(): void
    {
        $journal = new JournalData([
            'id' => 'journal-id',
            'campaign_id' => 'campaign-id',
            'title' => 'Session notes',
            'is_public' => true,
            'created_at' => '2026-01-01T00:00:00Z',
            'content_metadata' => ['version' => 1, 'editor' => 'tiptap'],
        ]);

        $this->assertSame(1, $journal->get('content_metadata.version'));
    }
}
