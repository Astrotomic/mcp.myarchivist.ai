<?php

namespace App\Mcp\Tools\Items;

use App\Actions\Archivist\Items\UpdateItem;
use App\Mcp\Tools\Tool;
use App\Mcp\WikilinkPrompts;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Partially update an item. '.WikilinkPrompts::UPDATE_COMPENDIUM_TEXT
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class UpdateItemTool extends Tool
{
    protected function action(): UpdateItem
    {
        return UpdateItem::make();
    }
}
