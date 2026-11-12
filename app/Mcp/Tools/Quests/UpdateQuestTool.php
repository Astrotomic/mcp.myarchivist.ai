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
    '(objectives, progress_log, related_characters, etc). To edit objectives or progress entries '.
    'without losing their identity, send objects with the ids from get_quest: objectives as '.
    '{"id", "text", "status"} and progress_log as {"id", "text", "session_id"?}. An entry with an id '.
    'keeps that id and its session; session_id, when sent, re-tags the entry (it must be a session in '.
    'this campaign). Entries without an id are new and default to the quest\'s latest session. Plain '.
    'strings still work but are matched by text only. Quests do NOT participate in the wikilinks '.
    'system, so text fields are stored verbatim.'
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
