<?php

namespace App\Mcp\Tools\Links;

use App\Actions\Archivist\Links\DeleteLink;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Delete a single link between entities. This removes the Link record but does NOT rewrite '.
    'the source record\'s text; any [[alias]] markup in the source description will remain until '.
    'the source is next edited. Use bulk_link_maintenance with operation=remove if you also want '.
    'the wikilink brackets stripped from all references to the target across the campaign.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class DeleteLinkTool extends Tool
{
    protected function action(): DeleteLink
    {
        return DeleteLink::make();
    }
}
