<?php

namespace App\Mcp\Tools\Onboarding;

use App\Actions\Archivist\Onboarding\GetStarted;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Title;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Title('Get started with Archivist')]
#[Description(
    'Returns the signed-in user\'s Archivist setup status, for requests to set up Archivist, get started, '.
    'see what Archivist can do, or add a first session. Includes: status (no_campaign, player_only, '.
    'empty_campaign, no_sessions_yet, has_sessions, subscription_inactive); the campaign to use, with the user\'s role in it; the '.
    'user\'s other campaigns; sessions remaining; whether the connection can write; whether a session can be '.
    'created now, and why not; a suggested next step; and Archivist\'s guided setup steps (playbook). '.
    'include_playbook: false omits the playbook. playbook_reference returns one reference section '.
    '(source-intake, paths, showcase or links) instead.'
)]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetStartedTool extends Tool
{
    protected function action(): GetStarted
    {
        return GetStarted::make();
    }
}
