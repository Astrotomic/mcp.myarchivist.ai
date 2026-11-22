<?php

namespace App\Actions\Archivist\Onboarding;

use App\Actions\Archivist\ApiAction;
use App\Data\GetStartedData;
use App\Exceptions\ArchivistApiException;
use App\Services\AuthContext;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\ValidatedInput;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

/**
 * One read that tells an assistant where a user stands in Archivist, plus the onboarding
 * setup guide (the same text as the archivist-get-started skill), so hosts that don't load
 * skills — a plain Claude connector, ChatGPT without the setup flow — still get it.
 */
final readonly class GetStarted extends ApiAction
{
    /** Campaigns to fetch stats for; the first few the user can manage is plenty for onboarding. */
    private const MAX_STATS_LOOKUPS = 3;

    private const STARTER_CAMPAIGN_TITLE = 'My First Campaign';

    private const SKILL_DIR = 'plugin/skills/archivist-get-started';

    /** Reference sections a caller can request one at a time with `guide_section`. */
    public const REFERENCE_SECTIONS = ['source-intake', 'paths', 'showcase', 'links'];

    public static function rules(): array
    {
        return [
            'include_setup_guide' => ['nullable', 'boolean'],
            'guide_section' => ['nullable', 'string', 'in:source-intake,paths,showcase,links'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/users/me');
    }

    public function execute(array $params): GetStartedData
    {
        $validated = Validator::make($params, self::rules())->safe();

        try {
            $me = $this->request($validated)->fluent()->all();
        } catch (ArchivistApiException $e) {
            // The API refuses agent connections for accounts without an active subscription, and
            // every other Archivist tool fails the same way. Say so plainly instead of offering
            // saves (journal, compendium) the account can't complete.
            if ($e->status === HttpStatus::HTTP_FORBIDDEN && str_contains(strtolower($e->detail), 'subscription inactive')) {
                return self::subscriptionInactive();
            }

            throw $e;
        }

        $reference = $validated->string('guide_section')->toString();
        $setupGuide = match (true) {
            $reference !== '' => self::reference($reference),
            $validated->boolean('include_setup_guide', true) => self::setupGuide(),
            default => null,
        };

        return $this->build($me, $setupGuide);
    }

    protected function map(array $data): GetStartedData
    {
        return $this->build($data, self::setupGuide());
    }

    /**
     * @param  array<string, mixed>  $data  GET /v1/users/me
     */
    private function build(array $data, ?string $setupGuide): GetStartedData
    {
        $campaigns = $this->campaigns();
        $manageable = array_values(array_filter(
            $campaigns,
            // A missing viewer_role means an older API; don't hide the user's own campaigns.
            fn (array $campaign): bool => in_array($campaign['viewer_role'] ?? null, ['owner', 'co_gm', null], true),
        ));

        $primary = null;
        foreach ($this->rankForOnboarding($manageable) as $index => $campaign) {
            if ($index >= self::MAX_STATS_LOOKUPS) {
                break;
            }
            $campaign = $this->withStats($campaign);
            $primary ??= $campaign;
            if (($campaign['sessions'] ?? 0) > 0) {
                // Prefer a campaign that already has sessions: that's where the user is playing.
                $primary = $campaign;
                break;
            }
        }

        $status = match (true) {
            $campaigns === [] => GetStartedData::STATUS_NO_CAMPAIGN,
            $primary === null => GetStartedData::STATUS_PLAYER_ONLY,
            ($primary['sessions'] ?? 0) > 0 => GetStartedData::STATUS_HAS_SESSIONS,
            ($primary['characters'] ?? 0) > 0 || ($primary['moments'] ?? 0) > 0 => GetStartedData::STATUS_NO_SESSIONS_YET,
            default => GetStartedData::STATUS_EMPTY_CAMPAIGN,
        };

        $sessionsRemaining = isset($data['text_sessions_remaining']) && is_int($data['text_sessions_remaining'])
            ? $data['text_sessions_remaining']
            : null;
        if (($primary['viewer_role'] ?? null) === 'co_gm') {
            // A co-GM's sessions come from the owner's allowance, which this account can't see.
            $sessionsRemaining = null;
        }
        $canWrite = app(AuthContext::class)->canWrite();
        [$canCreate, $reason] = $this->sessionEligibility($primary, $data, $sessionsRemaining, $canWrite);

        return new GetStartedData([
            'status' => $status,
            'status_detail' => $this->statusDetail($status, $canCreate, $canWrite),
            'campaign' => $primary === null ? null : [
                'id' => $primary['id'],
                'title' => $primary['title'],
                'role' => $primary['viewer_role'] ?? null,
                'sessions' => $primary['sessions'] ?? null,
                'characters' => $primary['characters'] ?? null,
                'moments' => $primary['moments'] ?? null,
                'url' => URL::toApp('/campaigns/{id}', ['id' => $primary['id']]),
            ],
            'campaigns' => array_map(fn (array $campaign): array => [
                'id' => $campaign['id'],
                'title' => $campaign['title'],
                'role' => $campaign['viewer_role'] ?? null,
            ], array_slice($campaigns, 0, 20)),
            'is_new_user' => isset($data['new_user']) ? (bool) $data['new_user'] : null,
            'sessions_remaining' => $sessionsRemaining,
            'can_write' => $canWrite,
            'can_create_session' => $canCreate,
            'can_create_session_reason' => $reason,
            'setup_guide' => $setupGuide,
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function campaigns(): array
    {
        $response = $this->client->get('/v1/campaigns', ['page' => 1, 'size' => 50]);

        $campaigns = [];
        foreach ((array) $response->fluent()->get('data', []) as $campaign) {
            if (is_array($campaign) && is_string($campaign['id'] ?? null) && ! ($campaign['archived'] ?? false)) {
                /** @var array<string, mixed> $campaign */
                $campaigns[] = $campaign;
            }
        }

        return $campaigns;
    }

    /**
     * The starter campaign first (it's where a new user's content belongs), then API order
     * (most recently updated first).
     *
     * @param  list<array<string, mixed>>  $campaigns
     * @return list<array<string, mixed>>
     */
    private function rankForOnboarding(array $campaigns): array
    {
        usort($campaigns, function (array $a, array $b): int {
            $aStarter = ($a['title'] ?? '') === self::STARTER_CAMPAIGN_TITLE;
            $bStarter = ($b['title'] ?? '') === self::STARTER_CAMPAIGN_TITLE;

            return $bStarter <=> $aStarter;
        });

        return $campaigns;
    }

    /**
     * @param  array<string, mixed>  $campaign
     * @return array<string, mixed>
     */
    private function withStats(array $campaign): array
    {
        $id = $campaign['id'] ?? null;
        if (! is_string($id)) {
            return $campaign;
        }

        try {
            $stats = $this->client->get("/v1/campaigns/{$id}/stats")->fluent();
        } catch (ArchivistApiException|ConnectionException) {
            return $campaign;
        }

        return [
            ...$campaign,
            'sessions' => $stats->integer('sessions'),
            'characters' => $stats->integer('characters'),
            'moments' => $stats->integer('moments'),
        ];
    }

    /**
     * @param  array<string, mixed>|null  $primary
     * @param  array<string, mixed>  $me
     * @return array{0: bool, 1: string|null}
     */
    private function sessionEligibility(?array $primary, array $me, ?int $sessionsRemaining, bool $canWrite): array
    {
        // Checked first: without write access no fallback (journal, compendium) is possible either.
        if (! $canWrite) {
            return [false, 'This connection is read-only: sessions, journal entries and compendium records can\'t be saved until Archivist is reconnected with write access.'];
        }

        if ($primary === null) {
            return [false, 'The user doesn\'t run any campaign; only a campaign owner or co-GM can add sessions.'];
        }

        $access = is_array($me['access'] ?? null) ? $me['access'] : [];
        $accessLevel = $access['level'] ?? null;
        if ($accessLevel === 'read_only') {
            return [false, 'New sessions are paused because the account\'s subscription is not active. Journal entries and compendium records can still be saved.'];
        }

        if ($sessionsRemaining !== null && $sessionsRemaining < 1) {
            return [false, 'No sessions are left in the current billing period. Journal entries and compendium records can still be saved.'];
        }

        return [true, null];
    }

    public static function subscriptionInactive(): GetStartedData
    {
        return new GetStartedData([
            'status' => GetStartedData::STATUS_SUBSCRIPTION_INACTIVE,
            'status_detail' => 'The account\'s subscription isn\'t active, so campaign data can\'t be read or saved through '
                .'this connection, and other Archivist tools will return the same error. Existing campaigns remain '
                .'available in the Archivist app.',
            'campaign' => null,
            'campaigns' => [],
            'is_new_user' => null,
            'sessions_remaining' => null,
            'can_write' => false,
            'can_create_session' => false,
            'can_create_session_reason' => 'The account\'s subscription is not active, so this connection can\'t save anything.',
            'setup_guide' => null,
        ]);
    }

    /**
     * A factual description of where the user stands. Guidance on what to do with it lives in
     * the setup guide (the archivist-get-started skill), not here.
     */
    private function statusDetail(string $status, bool $canCreate, bool $canWrite): string
    {
        if (! $canWrite) {
            return 'This connection is read-only. Campaign data can be read, but nothing can be saved until Archivist '
                .'is reconnected with write access.';
        }

        return match ($status) {
            GetStartedData::STATUS_NO_CAMPAIGN => 'The user has no campaign yet. A campaign can be created with '
                .'create-campaign-tool (a name and a game system).',
            GetStartedData::STATUS_PLAYER_ONLY => 'The user is a player in the listed campaigns, not a GM. Players can\'t '
                .'add sessions; they can keep their own journal entries, view their character, and ask questions about '
                .'the campaign.',
            GetStartedData::STATUS_EMPTY_CAMPAIGN => $canCreate
                ? 'The campaign is empty. A session can be created from the user\'s notes, recap or transcript with '
                    .'create-session-from-notes-tool; each new session uses one from the allowance.'
                : 'The campaign is empty. Sessions can\'t be created right now (see can_create_session_reason); journal '
                    .'entries and compendium records can still be saved.',
            GetStartedData::STATUS_NO_SESSIONS_YET => $canCreate
                ? 'The campaign has characters or other records but no sessions yet. A session can be created from the '
                    .'user\'s notes, recap or transcript with create-session-from-notes-tool; each new session uses one '
                    .'from the allowance.'
                : 'The campaign has characters or other records but no sessions yet. Sessions can\'t be created right '
                    .'now (see can_create_session_reason); journal entries and compendium records can still be saved.',
            default => 'The campaign already has sessions. More can be added the same way.',
        };
    }

    /**
     * Archivist's setup guide: the archivist-get-started skill's SKILL.md without its frontmatter,
     * for hosts that don't load plugin skills (e.g. a Claude connector without the plugin).
     * Reference sections are left out to keep the result small; a caller fetches one with
     * `guide_section`.
     */
    public static function setupGuide(): ?string
    {
        $skill = @file_get_contents(base_path(self::SKILL_DIR).'/SKILL.md');
        if (! is_string($skill)) {
            return null;
        }

        $body = trim((string) preg_replace('/\A---\R.*?\R---\R/s', '', $skill));

        return '<!-- Archivist setup guide. Reference sections (references/*.md) are available from '
            .'get-started-tool with guide_section: '.implode(', ', self::REFERENCE_SECTIONS).'. -->'."\n\n".$body;
    }

    public static function reference(string $section): ?string
    {
        if (! in_array($section, self::REFERENCE_SECTIONS, true)) {
            return null;
        }

        $content = @file_get_contents(base_path(self::SKILL_DIR)."/references/{$section}.md");

        return is_string($content) ? Str::of($content)->trim()->toString() : null;
    }
}
