<?php

namespace App\Mcp\Tools\Beats;

use App\Actions\Archivist\Beats\UpdateBeat;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Partially update a story beat. IMPORTANT: Beats use the explicit-link contract. On write, '.
    '[[alias]] markup in description is only preserved if a Link row already exists for this '.
    'beat with that alias; new wikilinks are silently dropped. To add a wikilink, call '.
    'create_link with from_type="Beat" first, then include [[alias]] in description. When '.
    'editing existing description, first fetch the beat with get_beat(with_links: true) to see '.
    'the current [[wikilinks]] and avoid losing them.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class UpdateBeatTool extends Tool
{
    protected function action(): UpdateBeat
    {
        return UpdateBeat::make();
    }
}
