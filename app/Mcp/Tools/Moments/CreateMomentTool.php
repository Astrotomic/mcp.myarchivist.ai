<?php

namespace App\Mcp\Tools\Moments;

use App\Actions\Archivist\Moments\CreateMoment;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Create a Key Moment (Moment) attached to a session. For a Key Moment card, set '.
    'categories=["key-moment"] and approved=true, pending=false. Optional image is a URL; to '.
    'upload a file, create first then use init_image_upload with entity_type=moment. metadata '.
    'holds presentation fields such as style, customTheme, and variant. IMPORTANT: Unlike '.
    'Characters/Factions/Locations/Items, moment content does NOT auto-resolve new [[wikilinks]]. '.
    'Only aliases that already have Link rows (created via create_link or authored during '.
    'transcript upload) are wrapped in the stored content. To add a wikilink referring to an '.
    'entity, first call create_link with from_type="Moment" and from_id set to the moment id, '.
    'then update_moment to include [[alias]] in content.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class CreateMomentTool extends Tool
{
    protected function action(): CreateMoment
    {
        return CreateMoment::make();
    }
}
