<?php

namespace App\Mcp\Tools\Moments;

use App\Actions\Archivist\Moments\DeleteMoment;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Delete a moment. Any inbound/outbound Link rows for this moment are removed. Note: as a '.
    'source of wikilinks, moments only appear as [[…]] in their own content (not in other '.
    'entities\' text), so no cross-record text rewriting is required.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class DeleteMomentTool extends Tool
{
    protected function action(): DeleteMoment
    {
        return DeleteMoment::make();
    }
}
