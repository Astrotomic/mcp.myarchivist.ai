<?php

namespace App\Mcp\Tools\Images;

use App\Actions\Archivist\Images\GenerateImage;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Server-side AI image generation for a Character, Faction, Location, Item, or World. The API '.
    'rewrites the prompt from the entity\'s stored description (blending in optional `user_input`) '.
    'and calls the configured provider. `type` is required and must be one of character, faction, '.
    'location, item, or world. For type=world, `entity_id` is optional and defaults to the campaign. '.
    'For every other type, `entity_id` must be a real record in the campaign. Consumes the '.
    'account\'s per-cycle image generation quota — call `get_image_usage` first to confirm '.
    'headroom. AI features are disabled on archived campaigns. Returns the public URL of the '.
    'generated image; the image is NOT automatically attached to the entity — set '.
    '`image` on the entity via the matching update tool if you want to persist it.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(true)]
class GenerateImageTool extends Tool
{
    protected function action(): GenerateImage
    {
        return GenerateImage::make();
    }
}
