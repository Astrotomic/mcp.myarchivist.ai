<?php

namespace App\Mcp\Tools\Sessions;

use App\Actions\Archivist\Sessions\PatchSession;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Partially update a game session (limited fields: title, session_date, summary, image). '.
    'IMPORTANT: Sessions use the explicit-link contract, not the auto-resolve contract used by '.
    'Character/Faction/Location/Item. On write, the API strips any [[alias]] markup whose alias '.
    'does NOT already have a Link row for this session; new wikilinks in summary are silently '.
    'dropped. To reference an entity, call create_link first with from_type="GameSession", then '.
    'include [[alias]] in the summary. When editing summary, first fetch the session with '.
    'get_session(with_links: true) to see current [[wikilinks]] and avoid losing them.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class PatchSessionTool extends Tool
{
    protected function action(): PatchSession
    {
        return PatchSession::make();
    }
}
