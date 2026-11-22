<?php

namespace App\Mcp\Tools\Onboarding;

use App\Actions\Archivist\Onboarding\GetStarted;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Start here when someone has just connected Archivist, is new, asks what Archivist can do or how to set it up, '.
    'wants to get their game into Archivist, or their campaign looks empty. Returns where the user stands '.
    '(status: no_campaign, player_only, empty_campaign, no_sessions_yet, has_sessions), the campaign to use, '.
    'how many sessions they have left, whether a session can be created now, a suggested next step, and the '.
    'onboarding playbook to follow: ask what they have, preview a draft, and save their first session, journal '.
    'or compendium records. Pass include_playbook: false if you already have it.'
)]
#[IsReadOnly(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetStartedTool extends Tool
{
    protected function action(): GetStarted
    {
        return GetStarted::make();
    }
}
