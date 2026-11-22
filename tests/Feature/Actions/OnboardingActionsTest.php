<?php

namespace Tests\Feature\Actions;

use App\Actions\Archivist\Onboarding\GetStarted;
use App\Actions\Archivist\Sessions\CreateSessionFromNotes;
use App\Data\GetStartedData;
use App\Data\TextSessionData;
use App\Exceptions\ArchivistApiException;
use App\Services\ArchivistClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

final class OnboardingActionsTest extends FeatureTestCase
{
    private const BASE_URL = 'https://api.myarchivist.test';

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.archivist.base_url', self::BASE_URL);
        config()->set('services.archivist.app_url', 'https://app.myarchivist.test');

        $this->app->instance(
            ArchivistClient::class,
            new ArchivistClient(token: 'test-token', usesApiKey: false),
        );
    }

    // --- create-session-from-notes ------------------------------------------------------

    #[Test]
    public function create_session_from_notes_posts_to_the_from_text_endpoint_with_the_host_label(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/sessions/from-text' => Http::response($this->fakeTextSession(), 201),
        ]);

        request()->headers->set('User-Agent', 'Claude-User/1.0');

        $result = CreateSessionFromNotes::make()->execute([
            'campaign_id' => 'w1',
            'text' => 'The party found the ferry wrecked.',
            'title' => 'Trouble at the Ferry',
            'idempotency_key' => 'draft-1',
        ]);

        $this->assertInstanceOf(TextSessionData::class, $result);
        $this->assertSame('c0123456789abcdef01234567', $result->get('session_id'));
        $this->assertFalse($result->get('replayed'));

        Http::assertSent(function (Request $request): bool {
            return $request->url() === self::BASE_URL.'/v1/sessions/from-text'
                && $request->method() === 'POST'
                && $request['campaign_id'] === 'w1'
                && $request['format'] === 'notes'
                && $request['title'] === 'Trouble at the Ferry'
                && $request['idempotency_key'] === 'draft-1'
                && ! array_key_exists('session_date', $request->data())
                && $request->header('X-Archivist-Client') === ['claude']
                && $request->header('Authorization') === ['Bearer test-token'];
        });
    }

    #[Test]
    public function create_session_from_notes_requires_an_idempotency_key(): void
    {
        Http::fake();
        Http::preventStrayRequests();

        $this->expectException(ValidationException::class);

        CreateSessionFromNotes::make()->execute([
            'campaign_id' => 'w1',
            'text' => 'Notes',
        ]);
    }

    #[Test]
    public function create_session_from_notes_surfaces_the_api_error_code(): void
    {
        Http::fake([
            self::BASE_URL.'/v1/sessions/from-text' => Http::response([
                'detail' => [
                    'code' => 'no_sessions_remaining',
                    'message' => 'No text sessions available.',
                ],
            ], 402),
        ]);

        try {
            CreateSessionFromNotes::make()->execute([
                'campaign_id' => 'w1',
                'text' => 'Notes',
                'idempotency_key' => 'k',
            ]);
            $this->fail('Expected an API exception');
        } catch (ArchivistApiException $e) {
            $this->assertSame(402, $e->status);
            $this->assertStringContainsString('no_sessions_remaining', $e->getMessage());
        }
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function userAgents(): array
    {
        return [
            'chatgpt' => ['openai-mcp/1.0.0', 'chatgpt'],
            'claude' => ['Claude-User', 'claude'],
            'anthropic' => ['anthropic-mcp-client', 'claude'],
            'unknown' => ['Cursor/1.2', 'other'],
            'empty' => ['', 'other'],
        ];
    }

    #[Test]
    #[DataProvider('userAgents')]
    public function host_label_is_derived_from_the_user_agent(string $userAgent, string $expected): void
    {
        $this->assertSame($expected, CreateSessionFromNotes::hostLabel($userAgent));
    }

    // --- get-started ------------------------------------------------------------------------

    #[Test]
    public function get_started_points_a_new_gm_at_their_empty_starter_campaign(): void
    {
        $this->fakeAccount(
            campaigns: [
                $this->fakeCampaign('w2', 'Side Project', 'owner'),
                $this->fakeCampaign('w1', 'My First Campaign', 'owner'),
            ],
            stats: ['w1' => [0, 0, 0], 'w2' => [0, 0, 0]],
        );

        $result = GetStarted::make()->execute([]);

        $this->assertInstanceOf(GetStartedData::class, $result);
        $this->assertSame('empty_campaign', $result->get('status'));
        $this->assertSame('w1', $result->get('campaign')['id'], 'the starter campaign wins');
        $this->assertSame('https://app.myarchivist.test/campaigns/w1', $result->get('campaign')['url']);
        $this->assertSame(2, $result->get('sessions_remaining'));
        $this->assertTrue($result->get('can_write'));
        $this->assertTrue($result->get('can_create_session'));
        $this->assertTrue($result->get('is_new_user'));
        $this->assertStringContainsString('Get started with Archivist', $result->get('playbook'));
        $this->assertStringContainsString('references/source-intake.md', $result->get('playbook'));
        $this->assertStringNotContainsString('description: First-run onboarding', $result->get('playbook'), 'frontmatter is stripped');
    }

    #[Test]
    public function get_started_prefers_a_campaign_that_already_has_sessions(): void
    {
        $this->fakeAccount(
            campaigns: [
                $this->fakeCampaign('w1', 'My First Campaign', 'owner'),
                $this->fakeCampaign('w2', 'Curse of the Ferry', 'owner'),
            ],
            stats: ['w1' => [0, 0, 0], 'w2' => [3, 12, 9]],
        );

        $result = GetStarted::make()->execute(['include_playbook' => false]);

        $this->assertSame('has_sessions', $result->get('status'));
        $this->assertSame('w2', $result->get('campaign')['id']);
        $this->assertSame(3, $result->get('campaign')['sessions']);
        $this->assertNull($result->get('playbook'));
    }

    #[Test]
    public function get_started_reports_players_who_cannot_add_sessions(): void
    {
        $this->fakeAccount(
            campaigns: [$this->fakeCampaign('w9', "The GM's Table", 'player')],
            stats: [],
        );

        $result = GetStarted::make()->execute(['include_playbook' => false]);

        $this->assertSame('player_only', $result->get('status'));
        $this->assertNull($result->get('campaign'));
        $this->assertFalse($result->get('can_create_session'));
        $this->assertSame([['id' => 'w9', 'title' => "The GM's Table", 'role' => 'player']], $result->get('campaigns'));
        Http::assertNotSent(fn (Request $request): bool => str_contains($request->url(), '/stats'));
    }

    #[Test]
    public function get_started_hides_the_co_gms_own_allowance_since_the_owners_is_spent(): void
    {
        $this->fakeAccount(
            campaigns: [$this->fakeCampaign('w3', 'Shared Table', 'co_gm')],
            stats: ['w3' => [0, 0, 0]],
            textSessionsRemaining: 0,
        );

        $result = GetStarted::make()->execute(['include_playbook' => false]);

        $this->assertSame('co_gm', $result->get('campaign')['role']);
        $this->assertNull($result->get('sessions_remaining'));
        $this->assertTrue($result->get('can_create_session'));
    }

    #[Test]
    public function get_started_reports_no_campaign(): void
    {
        $this->fakeAccount(campaigns: [], stats: []);

        $result = GetStarted::make()->execute(['include_playbook' => false]);

        $this->assertSame('no_campaign', $result->get('status'));
        $this->assertFalse($result->get('can_create_session'));
    }

    #[Test]
    public function get_started_says_no_session_can_be_created_when_the_allowance_is_spent(): void
    {
        $this->fakeAccount(
            campaigns: [$this->fakeCampaign('w1', 'My First Campaign', 'owner')],
            stats: ['w1' => [0, 2, 0]],
            textSessionsRemaining: 0,
        );

        $result = GetStarted::make()->execute(['include_playbook' => false]);

        $this->assertSame('no_sessions_yet', $result->get('status'));
        $this->assertFalse($result->get('can_create_session'));
        $this->assertStringContainsString('journal', $result->get('can_create_session_reason'));
    }

    #[Test]
    public function get_started_says_a_read_only_connection_cannot_create_sessions(): void
    {
        $this->fakeAccount(
            campaigns: [$this->fakeCampaign('w1', 'My First Campaign', 'owner')],
            stats: ['w1' => [0, 0, 0]],
            scopes: ['agent_read'],
        );

        $result = GetStarted::make()->execute(['include_playbook' => false]);

        $this->assertFalse($result->get('can_write'));
        $this->assertFalse($result->get('can_create_session'));
        $this->assertStringContainsString('read-only', $result->get('can_create_session_reason'));
        // Journals are writes too, so a read-only connection must not be steered to them.
        $this->assertStringContainsString('reconnect', $result->get('next_step'));
        $this->assertStringNotContainsString('journal', $result->get('next_step'));
        $this->assertStringNotContainsString('Save notes as a journal', $result->get('can_create_session_reason'));
    }

    // --- fixtures -----------------------------------------------------------------------

    /**
     * @param  list<array<string, mixed>>  $campaigns
     * @param  array<string, array{0: int, 1: int, 2: int}>  $stats  [sessions, characters, moments]
     * @param  list<string>  $scopes
     */
    private function fakeAccount(array $campaigns, array $stats, int $textSessionsRemaining = 2, array $scopes = ['agent_read', 'agent_write']): void
    {
        $responses = [
            self::BASE_URL.'/v1/users/me' => Http::response([
                'id' => 'u1',
                'name' => 'GM',
                'new_user' => true,
                'subscription_tier' => 'trial',
                'subscription_status' => 'trialing',
                'any_sessions_remaining' => $textSessionsRemaining,
                'text_sessions_remaining' => $textSessionsRemaining,
                'scopes' => $scopes,
                'api_profile' => 'agent',
                'access' => ['level' => 'full', 'subscription_status' => 'trialing', 'can_create_campaign' => true],
            ]),
            self::BASE_URL.'/v1/campaigns*' => Http::response([
                'data' => $campaigns,
                'total' => count($campaigns),
                'page' => 1,
                'size' => 50,
                'pages' => 1,
            ]),
        ];

        foreach ($stats as $id => [$sessions, $characters, $moments]) {
            $responses[self::BASE_URL."/v1/campaigns/{$id}/stats"] = Http::response([
                'campaign_id' => $id,
                'title' => $id,
                'characters' => $characters,
                'sessions' => $sessions,
                'moments' => $moments,
                'public' => false,
                'created_at' => '2026-11-20T00:00:00Z',
            ]);
            // GetCampaignStats-style secondary lookups are not made by get-started.
        }

        // Specific stats URLs must be matched before the campaigns wildcard.
        $ordered = array_filter($responses, fn (string $url): bool => str_contains($url, '/stats'), ARRAY_FILTER_USE_KEY)
            + $responses;

        Http::fake($ordered);
        Http::preventStrayRequests();
    }

    /**
     * @return array<string, mixed>
     */
    private function fakeCampaign(string $id, string $title, string $role): array
    {
        return [
            'id' => $id,
            'title' => $title,
            'description' => null,
            'system' => 'D&D 5e',
            'image' => null,
            'public' => false,
            'mature' => false,
            'created_at' => '2026-11-20T00:00:00Z',
            'updated_at' => null,
            'viewer_role' => $role,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function fakeTextSession(): array
    {
        return [
            'session_id' => 'c0123456789abcdef01234567',
            'campaign_id' => 'w1',
            'title' => 'Trouble at the Ferry',
            'type' => 'rawNotes',
            'session_date' => '2026-11-20T00:00:00.000Z',
            'review_stage' => 'queued',
            'replayed' => false,
            'url' => 'https://app.myarchivist.test/gamesessions/c0123456789abcdef01234567',
        ];
    }
}
