<?php

namespace App\Mcp\Tools\Links;

use App\Actions\Archivist\Links\UpdateLink;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Update an existing link\'s alias. Only the alias field can be changed via this tool; to '.
    'change the source or target of a link, delete it and create a new one, or use '.
    'bulk_link_maintenance to rename an alias campaign-wide.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class UpdateLinkTool extends Tool
{
    protected function action(): UpdateLink
    {
        return UpdateLink::make();
    }
}
