<?php

namespace App\Mcp\Tools\Moments;

use App\Actions\Archivist\Moments\UpdateMoment;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Partially update a moment. IMPORTANT: Moments use the explicit-link contract, not the '.
    'auto-resolve contract used by Character/Faction/Location/Item. On write, the API strips '.
    'any [[alias]] markup whose alias does NOT already have a Link row for this moment; new '.
    'wikilinks in content are silently dropped. To reference an entity, call create_link first '.
    'with from_type="Moment", then include [[alias]] in the content on this update. When '.
    'editing existing content, fetch the moment with get_moment(with_links: true) first to see '.
    'the current [[wikilinks]] and avoid inadvertently losing them.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class UpdateMomentTool extends Tool
{
    protected function action(): UpdateMoment
    {
        return UpdateMoment::make();
    }
}
