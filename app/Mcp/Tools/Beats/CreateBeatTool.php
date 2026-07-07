<?php

namespace App\Mcp\Tools\Beats;

use App\Actions\Archivist\Beats\CreateBeat;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Create a new story beat in a campaign. Beats organize the campaign timeline (major/minor/'.
    'step) and can be attached to one or more sessions or nested under a parent beat. '.
    'IMPORTANT: Like sessions, beat descriptions use the explicit-link contract — new '.
    '[[wikilinks]] in description are silently dropped unless a matching Link already exists '.
    'for this beat. To reference an entity, create the beat first, then call create_link with '.
    'from_type="Beat", then update_beat to include [[alias]] in the description.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class CreateBeatTool extends Tool
{
    protected function action(): CreateBeat
    {
        return CreateBeat::make();
    }
}
