<?php

namespace App\Mcp\Tools\Moments;

use App\Actions\Archivist\ApiAction;
use App\Actions\Archivist\Moments\GetMoment;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Get a Key Moment (Moment) by ID, including image URL, categories, kind, and presentation '.
    'metadata. Pass with_links=true before editing content. Attach or replace the card image '.
    'with init_image_upload (entity_type=moment) then complete_image_upload.'
)]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetMomentTool extends Tool
{
    protected function action(): ApiAction
    {
        return GetMoment::make();
    }
}
