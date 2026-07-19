<?php

namespace App\Mcp\Tools\Links;

use App\Actions\Archivist\Links\BulkLinkMaintenance;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Trigger campaign-wide wikilink maintenance for a target entity (Character/Faction/Location/'.
    'Item/Moment). Operations: (1) add — introduce a new [[alias]] pointing at target_id in '.
    'every text field currently containing that alias in plain form; (2) remove — unbracket '.
    'every [[alias]] that resolves to target_id; (3) update — repoint every existing link to '.
    'target_id to new_target_id, and/or rename its alias to new_alias. The request is '.
    'accepted (202) and forwarded to a background webhook; the response contains a task_id '.
    'you can use to correlate downstream logs. Only the campaign owner can trigger this and '.
    'the account must have an active subscription tier.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class BulkLinkMaintenanceTool extends Tool
{
    protected function action(): BulkLinkMaintenance
    {
        return BulkLinkMaintenance::make();
    }
}
