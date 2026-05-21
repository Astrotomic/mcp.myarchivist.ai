<?php

namespace App\Mcp\Tools\Items;

use App\Actions\Archivist\Items\ListItems;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List items in a campaign. Optionally filter by name search. Items include weapons, armour, artefacts, and other notable objects.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListItemsTool extends Tool
{
    protected function action(): ListItems
    {
        return ListItems::make();
    }
}
