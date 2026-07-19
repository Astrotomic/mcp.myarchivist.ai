<?php

namespace App\Mcp\Tools\Images;

use App\Actions\Archivist\Images\DeleteEntityImage;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Remove an image within a campaign. Provide EITHER `entity_type` + `entity_id` (detaches the '.
    'image from that record AND deletes the underlying object) OR a managed `image_url` (deletes '.
    'just that object). Valid entity types: campaign, world, character, faction, location, item, '.
    'moment, session, gamesession. Returns `{removed: bool}`.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class DeleteEntityImageTool extends Tool
{
    protected function action(): DeleteEntityImage
    {
        return DeleteEntityImage::make();
    }
}
