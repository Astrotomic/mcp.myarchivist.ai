<?php

namespace App\Mcp\Tools\Items;

use App\Actions\Archivist\Items\DeleteItem;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Delete an item. The API cleans up all outgoing and inbound Link rows and unbrackets '.
    '[[alias]] markup for this item in every referring text field.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class DeleteItemTool extends Tool
{
    protected function action(): DeleteItem
    {
        return DeleteItem::make();
    }
}
