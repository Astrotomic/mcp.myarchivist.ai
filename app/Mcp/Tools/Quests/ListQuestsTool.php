<?php

namespace App\Mcp\Tools\Quests;

use App\Actions\Archivist\Quests\ListQuests;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('List quests in a campaign with pagination. Filter by status (planned, in-progress, blocked, failed, done, n/a) or category (main, side, faction, personal, n/a).')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class ListQuestsTool extends Tool
{
    protected function action(): ListQuests
    {
        return ListQuests::make();
    }
}
