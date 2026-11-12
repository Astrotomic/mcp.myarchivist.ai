<?php

namespace App\Mcp\Tools\Characters;

use App\Actions\Archivist\Characters\ReviewCharacterArcRun;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Approve or decline all pending Character Arc changes from one session-processing run (the '.
    'Approve all / Decline buttons of the Character Arc Changes dialog). Get run_id from pending facets '.
    '(get_character_arc with status: "pending"). approve makes them canon. decline deletes the run\'s '.
    'proposed facets and restores the versions they replaced, so the session can be processed again. '.
    'To accept a run with corrections: fix individual pending facets with update_character_arc_facet or '.
    'delete_character_arc_facet first, then approve.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class ReviewCharacterArcRunTool extends Tool
{
    protected function action(): ReviewCharacterArcRun
    {
        return ReviewCharacterArcRun::make();
    }
}
