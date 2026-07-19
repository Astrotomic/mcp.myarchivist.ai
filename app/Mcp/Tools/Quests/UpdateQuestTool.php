<?php

namespace App\Mcp\Tools\Quests;

use App\Actions\Archivist\Quests\UpdateQuest;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Partially update a quest. Any list you send replaces the corresponding list on the record '.
    '(objectives, progress_log, related_characters, etc). Quests do NOT participate in the '.
    'wikilinks system, so text fields are stored verbatim.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class UpdateQuestTool extends Tool
{
    protected function action(): UpdateQuest
    {
        return UpdateQuest::make();
    }
}
