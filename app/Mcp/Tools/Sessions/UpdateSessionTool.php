<?php

namespace App\Mcp\Tools\Sessions;

use App\Actions\Archivist\Sessions\UpdateSession;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Fully update a game session (PUT). Fields not included may be reset to defaults. '.
    'IMPORTANT: Sessions use the explicit-link contract like patch_session — [[wikilinks]] in '.
    'summary/notes are only preserved if a matching Link already exists (create_link with '.
    'from_type="GameSession" first). Fetch the session with get_session(with_links: true) '.
    'before editing to preserve existing wikilinks.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(true)]
class UpdateSessionTool extends Tool
{
    protected function action(): UpdateSession
    {
        return UpdateSession::make();
    }
}
