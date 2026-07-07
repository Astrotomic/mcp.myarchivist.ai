<?php

namespace App\Mcp\Tools\Beats;

use App\Actions\Archivist\Beats\DeleteBeat;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Delete a story beat. Child beats have their parent_id cleared. Any Link rows sourced from '.
    'this beat are removed.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class DeleteBeatTool extends Tool
{
    protected function action(): DeleteBeat
    {
        return DeleteBeat::make();
    }
}
