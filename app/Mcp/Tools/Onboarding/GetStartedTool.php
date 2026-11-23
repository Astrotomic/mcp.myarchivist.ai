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
    'created now, and why not; a short factual status_detail; and Archivist\'s setup guide (the same text as the '.
    'archivist-get-started skill). '.
    'include_setup_guide: false omits the guide (for clients that already have the skill). guide_section '.
    'returns one reference section '.
    '(source-intake, paths, showcase or links) instead. Connector-only clients need the setup guide, which is '.
    'included by default. can_create_session reports eligibility. Each save still needs a preview followed by '.
    'a separate explicit user reply approving that save.'
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
