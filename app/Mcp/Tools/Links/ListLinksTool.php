<?php

namespace App\Mcp\Tools\Links;

use App\Actions\Archivist\Links\ListLinks;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List links between entities in a campaign. Supports filtering by source/target entity and relationship alias.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListLinksTool extends Tool
{
    protected function action(): ListLinks
    {
        return ListLinks::make();
    }
}
