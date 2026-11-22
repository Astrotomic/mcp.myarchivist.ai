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

    /** Reference files appended to the playbook, in the order SKILL.md introduces them. */
    private const REFERENCE_FILES = ['source-intake.md', 'paths.md', 'showcase.md', 'links.md'];

    public static function rules(): array
    {
        return [
            'include_playbook' => ['nullable', 'boolean'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/users/me');
    }

    public function execute(array $params): GetStartedData
    {
        $validated = Validator::make($params, self::rules())->safe();
        $me = $this->request($validated)->fluent()->all();

        return $this->build($me, $validated->boolean('include_playbook', true));
    }

    protected function map(array $data): GetStartedData
    {
        return $this->build($data, true);
    }

    /**
     * @param  array<string, mixed>  $data  GET /v1/users/me
     */
    private function build(array $data, bool $includePlaybook): GetStartedData
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
            'subscription_tier' => is_string($data['subscription_tier'] ?? null) ? $data['subscription_tier'] : null,
            'subscription_status' => is_string($data['subscription_status'] ?? null) ? $data['subscription_status'] : null,
            'sessions_remaining' => $sessionsRemaining,
            'can_write' => $canWrite,
            'can_create_session' => $canCreate,
            'can_create_session_reason' => $reason,
            'playbook' => $includePlaybook ? self::playbook() : null,
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
            return [false, 'The subscription is inactive, so new sessions are paused. Save notes as a journal and point the user to plans.'];
        }

        if ($sessionsRemaining !== null && $sessionsRemaining < 1) {
            return [false, 'No sessions remain in this billing period. Save notes as a journal and point the user to plans.'];
        }

        return [true, null];
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
     * SKILL.md (without frontmatter) followed by its reference files, so the whole playbook
     * travels in one tool result.
     */
    public static function playbook(): ?string
    {
        $dir = base_path(self::SKILL_DIR);
        $skill = @file_get_contents("{$dir}/SKILL.md");
        if (! is_string($skill)) {
            return null;
        }

        $parts = [
            '<!-- The reference files mentioned below are included after the main playbook. -->',
            trim((string) preg_replace('/\A---\R.*?\R---\R/s', '', $skill)),
        ];

        foreach (self::REFERENCE_FILES as $file) {
            $content = @file_get_contents("{$dir}/references/{$file}");
            if (is_string($content)) {
                $parts[] = "---\n\n<!-- references/{$file} -->\n\n".trim($content);
            }
        }

        return Str::of(implode("\n\n", $parts))->trim()->toString();
    }
}
