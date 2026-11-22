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

/**
 * One read that tells an assistant where a user stands in Archivist, plus the onboarding
 * playbook (the same text as the archivist-get-started skill), so hosts that don't load
 * skills — a plain Claude connector, ChatGPT without the setup flow — still get it.
 */
final readonly class GetStarted extends ApiAction
{
    /** Campaigns to fetch stats for; the first few the user can manage is plenty for onboarding. */
    private const MAX_STATS_LOOKUPS = 3;

    private const STARTER_CAMPAIGN_TITLE = 'My First Campaign';

    private const SKILL_DIR = 'plugin/skills/archivist-get-started';

    /** Reference sections a caller can request one at a time with `playbook_reference`. */
    public const REFERENCE_SECTIONS = ['source-intake', 'paths', 'showcase', 'links'];

    public static function rules(): array
    {
        return [
            'include_playbook' => ['nullable', 'boolean'],
            'playbook_reference' => ['nullable', 'string', 'in:source-intake,paths,showcase,links'],
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
            if ($e->status === 403 && str_contains(strtolower($e->detail), 'subscription inactive')) {
                return self::subscriptionInactive();
            }

            throw $e;
        }

        $reference = $validated->string('playbook_reference')->toString();
        $playbook = match (true) {
            $reference !== '' => self::reference($reference),
            $validated->boolean('include_playbook', true) => self::playbook(),
            default => null,
        };

        return $this->build($me, $playbook);
    }

    protected function map(array $data): GetStartedData
    {
        return $this->build($data, self::playbook());
    }

    /**
     * @param  array<string, mixed>  $data  GET /v1/users/me
     */
    private function build(array $data, ?string $playbook): GetStartedData
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
            'next_step' => $this->nextStep($status, $canCreate, $canWrite),
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
            'playbook' => $playbook,
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
            return [false, 'This connection is read-only, so nothing can be saved: no session, journal or compendium record. Ask the user to reconnect Archivist and approve write access before trying to save anything.'];
        }

        if ($primary === null) {
            return [false, 'The user has no campaign they run. Only a campaign owner or co-GM can add sessions.'];
        }

        $access = is_array($me['access'] ?? null) ? $me['access'] : [];
        $accessLevel = $access['level'] ?? null;
        if ($accessLevel === 'read_only') {
            return [false, 'New sessions are paused because the account\'s subscription is not active. Their material can still be saved as a journal entry or compendium records.'];
        }

        if ($sessionsRemaining !== null && $sessionsRemaining < 1) {
            return [false, 'No sessions are left in the current billing period. Their material can still be saved as a journal entry or compendium records.'];
        }

        return [true, null];
    }

    public static function subscriptionInactive(): GetStartedData
    {
        return new GetStartedData([
            'status' => GetStartedData::STATUS_SUBSCRIPTION_INACTIVE,
            'next_step' => 'This account\'s subscription isn\'t active, so Archivist can\'t read or save campaign data '
                .'through this connection right now. Explain that plainly and don\'t attempt any other Archivist tool. '
                .'Everything already in their campaigns is still in the Archivist app.',
            'campaign' => null,
            'campaigns' => [],
            'is_new_user' => null,
            'sessions_remaining' => null,
            'can_write' => false,
            'can_create_session' => false,
            'can_create_session_reason' => 'The account\'s subscription is not active, so this connection can\'t save anything.',
            'playbook' => null,
        ]);
    }

    private function nextStep(string $status, bool $canCreate, bool $canWrite): string
    {
        if (! $canWrite) {
            return 'This connection is read-only. Tell the user what Archivist can do, answer questions with read tools, and ask them to reconnect Archivist with write access before saving anything. Do not attempt any save.';
        }

        return match ($status) {
            GetStartedData::STATUS_NO_CAMPAIGN => 'Ask for the campaign name and system in one line, create it, then follow the playbook.',
            GetStartedData::STATUS_PLAYER_ONLY => 'Follow the player path: their character, their own journal notes, and questions via ask-campaign. Players cannot add sessions.',
            GetStartedData::STATUS_EMPTY_CAMPAIGN, GetStartedData::STATUS_NO_SESSIONS_YET => $canCreate
                ? 'Follow the playbook: ask what they have, preview a draft, and with their confirmation create their first session from it.'
                : 'Follow the playbook, saving their material as a journal or compendium records instead of a session.',
            default => 'Skip the welcome. Ask what they want to add next; the next session uses the same intake.',
        };
    }

    /**
     * The main onboarding playbook: SKILL.md without its frontmatter. Reference sections are
     * left out to keep the result small; a caller fetches one with `playbook_reference`.
     */
    public static function playbook(): ?string
    {
        $skill = @file_get_contents(base_path(self::SKILL_DIR).'/SKILL.md');
        if (! is_string($skill)) {
            return null;
        }

        $body = trim((string) preg_replace('/\A---\R.*?\R---\R/s', '', $skill));

        return '<!-- Reference sections (references/*.md) are available from get-started-tool with '
            .'playbook_reference: '.implode(', ', self::REFERENCE_SECTIONS).'. -->'."\n\n".$body;
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
