<?php

namespace App\Mcp\Tools\Quests;

use App\Actions\Archivist\Quests\CreateQuest;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Create a new quest entry in a campaign\'s quest log. Objectives is a list of objects with '.
    '{text, status} where status is pending/in-progress/completed/failed/blocked. related_'.
    'characters/factions/locations/items are lists of entity ids or names. Quests use snake_'.
    'case field names in their body. Quests do NOT participate in the wikilinks system: text '.
    'fields (success_definition, failure_conditions, next_action, resolution) are stored '.
    'verbatim without link syncing.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class CreateQuestTool extends Tool
{
    protected function action(): CreateQuest
    {
        return CreateQuest::make();
    }
}
