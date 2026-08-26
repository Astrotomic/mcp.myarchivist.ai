<?php

namespace Tests\Feature\Actions;

use App\Actions\Archivist\Campaigns\GetCampaignStats;
use App\Data\CampaignStatsData;
use App\Data\SessionDataShort;
use App\Services\ArchivistClient;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

final class CampaignStatsAndSessionReviewTest extends FeatureTestCase
{
    private const BASE_URL = 'https://api.myarchivist.test';

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.archivist.base_url', self::BASE_URL);
        $this->app->instance(
            ArchivistClient::class,
            new ArchivistClient(token: 'test-token'),
        );
    }

    #[Test]
    public function campaign_stats_uses_mcp_visible_character_total(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/campaigns/camp_1/stats' => Http::response([
                'campaign_id' => 'camp_1',
                'title' => 'Tuesday Strahd',
                'characters' => 34,
                'sessions' => 2,
                'moments' => 5,
                'public' => false,
                'created_at' => '2026-08-18T00:00:00Z',
            ], 200),
            self::BASE_URL.'/v1/characters*' => Http::response([
                'data' => [],
                'total' => 20,
                'page' => 1,
                'size' => 1,
                'pages' => 20,
            ], 200),
        ]);

        $result = GetCampaignStats::make()->execute(['campaign_id' => 'camp_1']);

        $this->assertInstanceOf(CampaignStatsData::class, $result);
        $this->assertSame(20, $result->get('characters'));
        $this->assertSame(2, $result->get('sessions'));
        $this->assertSame(5, $result->get('moments'));
    }

    #[Test]
    public function session_data_accepts_compact_review_status(): void
    {
        $session = new SessionDataShort([
            'id' => 'session_2',
            'campaign_id' => 'camp_1',
            'type' => 'other',
            'title' => 'Session 2',
            'summary' => null,
            'session_date' => '2026-08-25T23:00:00Z',
            'public' => false,
            'notes' => null,
            'image' => null,
            'index' => 1,
            'pbp_start_msg_url' => null,
            'pbp_end_msg_url' => null,
            'ai_session_review' => [
                'stage' => 'review',
                'review_started_at' => '2026-08-25T23:10:00Z',
                'save_started_at' => null,
            ],
            'created_at' => '2026-08-25T23:00:00Z',
            'updated_at' => '2026-08-25T23:13:00Z',
        ]);

        $this->assertSame('review', $session->get('ai_session_review')['stage']);
        $this->assertNull($session->get('summary'));
    }
}
