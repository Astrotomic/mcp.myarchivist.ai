<?php

namespace App\Mcp\Tools\Items;

use App\Actions\Archivist\Items\CreateItem;
use App\Mcp\Tools\Tool;
use App\Mcp\WikilinkPrompts;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Create a new item in a campaign. '.WikilinkPrompts::CREATE_COMPENDIUM_TEXT
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class CreateItemTool extends Tool
{
    protected function action(): CreateItem
    {
        return CreateItem::make();
    }
}
