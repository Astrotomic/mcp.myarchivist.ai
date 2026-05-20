<?php

namespace Tests\Gummibeer\Mcp\Prompts;

use App\Mcp\Prompts\ReviewSessionPrompt;
use App\Mcp\Servers\ArchivistServer;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\Gummibeer\TestCase;

final class ReviewSessionPromptTest extends TestCase
{
    protected function setUp(): void
    {
        putenv('ARCHIVIST_TOKEN=fake-token');
        parent::setUp();
    }

    #[Test]
    public function it_returns_messages_and_resources(): void
    {
        Http::fake([
            'https://api.myarchivist.ai/v1/sessions/cmnhoa5e6000004juew4mv3o2' => Http::response([
                'id' => 'cmnhoa5e6000004juew4mv3o2',
                'campaign_id' => 'cmj78gm6k000004jrvzm7gcjr',
                'summary' => 'The party explored the cave.',
                'public' => true,
                'created_at' => now()->toIso8601String(),
            ]),
            'https://api.myarchivist.ai/v1/sessions/cmnhoa5e6000004juew4mv3o2/transcript' => Http::response([
                'id' => 'transcript-1',
                'session_id' => 'cmnhoa5e6000004juew4mv3o2',
                'transcript' => [
                    ['speaker' => 'GM', 'text' => 'You enter the cave.'],
                ],
                'created_at' => now()->toIso8601String(),
            ]),
        ]);

        ArchivistServer::prompt(ReviewSessionPrompt::class, [
            'session_id' => 'cmnhoa5e6000004juew4mv3o2',
            'language' => 'German',
        ])
            ->assertOk()
            ->assertDescription('Review a D&D session with summary and transcript.')
            ->assertSee('You are an expert TTRPG session reviewer.')
            ->assertSee('Please generate the complete session review in the following language: German.');
    }
}
