<?php

namespace App\Mcp\Tools\Characters;

use App\Actions\Archivist\Characters\DeleteCharacterArcFacet;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Delete a current Character Arc facet. If it replaced an earlier version (an edit or a '.
    'session-processing update), that earlier version becomes current again and its id is returned as '.
    'restored_id; delete that too to remove the facet entirely.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class DeleteCharacterArcFacetTool extends Tool
{
    protected function action(): DeleteCharacterArcFacet
    {
        return DeleteCharacterArcFacet::make();
    }
}
