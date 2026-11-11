<?php

namespace App\Mcp\Tools\Characters;

use App\Actions\Archivist\Characters\UpdateCharacterArcFacet;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Edit a current Character Arc facet (title, description, theme and/or session_id). '.
    'Changing title or description creates a NEW VERSION, exactly like an edit in the web app: the '.
    'returned facet has a new id, the old text is kept in its history (replaces_id / '.
    'previous_description), and later calls must use the new id. Changing only theme or session_id '.
    'retags the facet in place. Editing a pending facet accepts your corrected text; approve the rest '.
    'of the run with review_character_arc_run.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class UpdateCharacterArcFacetTool extends Tool
{
    protected function action(): UpdateCharacterArcFacet
    {
        return UpdateCharacterArcFacet::make();
    }
}
