<?php

namespace App\Mcp\Tools\Quests;

use App\Actions\Archivist\Quests\DeleteQuest;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Delete a quest and its objectives / progress entries / related refs.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(true)]
#[IsOpenWorld(false)]
class DeleteQuestTool extends Tool
{
    protected function action(): DeleteQuest
    {
        return DeleteQuest::make();
    }
}
