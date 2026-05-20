<?php

namespace App\Mcp\Tools\Quests;

use App\Actions\Archivist\ApiAction;
use App\Actions\Archivist\Quests\GetQuest;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Get a fully expanded quest by ID, including objectives, progress log, related entity refs, and session provenance.')]
#[IsReadOnly(true)]
#[IsDestructive(false)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class GetQuestTool extends Tool
{
    protected function action(): ApiAction
    {
        return GetQuest::make();
    }
}
