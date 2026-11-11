<?php

namespace App\Mcp\Tools\Characters;

use App\Actions\Archivist\Characters\GetCharacterArc;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Get a player character\'s Character Arc: facets grouped under the campaign\'s arc themes '.
    '(Character Development, Relationships, Combat & Capability, Knowledge & Discovery, '.
    'Reputation & Affiliation, Items & Resources, Conditions & Consequences). Each facet has its '.
    'id, text, session (session_id/session_number), status and version_count. '.
    'status: "approved" = the canonical arc as the web app shows it; "pending" = changes '.
    'proposed by session processing that await review (the Character Arc Changes dialog), each with '.
    'run_id and, for updates, previous_description (the text it would replace); "all" (default) = '.
    'every current facet tagged with its status. Ignore facets with hidden: true (internal markers). '.
    'Optional session_id filters to facets tagged with that session.'
)]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetCharacterArcTool extends Tool
{
    protected function action(): GetCharacterArc
    {
        return GetCharacterArc::make();
    }
}
