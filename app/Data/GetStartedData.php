<?php

namespace App\Data;

class GetStartedData extends ArchivistDto
{
    public const STATUS_NO_CAMPAIGN = 'no_campaign';

    public const STATUS_PLAYER_ONLY = 'player_only';

    public const STATUS_EMPTY_CAMPAIGN = 'empty_campaign';

    public const STATUS_NO_SESSIONS_YET = 'no_sessions_yet';

    public const STATUS_HAS_SESSIONS = 'has_sessions';

    public static function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:no_campaign,player_only,empty_campaign,no_sessions_yet,has_sessions'],
            'next_step' => ['required', 'string'],
            'campaign' => ['nullable', 'array'],
            'campaign.id' => ['required_with:campaign', 'string'],
            'campaign.title' => ['required_with:campaign', 'string'],
            'campaign.role' => ['nullable', 'string'],
            'campaign.sessions' => ['nullable', 'integer'],
            'campaign.characters' => ['nullable', 'integer'],
            'campaign.moments' => ['nullable', 'integer'],
            'campaign.url' => ['required_with:campaign', 'string'],
            'campaigns' => ['present', 'list'],
            'is_new_user' => ['nullable', 'boolean'],
            'subscription_tier' => ['nullable', 'string'],
            'subscription_status' => ['nullable', 'string'],
            'sessions_remaining' => ['nullable', 'integer'],
            'can_create_session' => ['required', 'boolean'],
            'can_create_session_reason' => ['nullable', 'string'],
            'playbook' => ['nullable', 'string'],
        ];
    }
}
