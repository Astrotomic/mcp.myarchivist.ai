<?php

namespace App\Mcp\Tools\Links;

use App\Actions\Archivist\Links\CreateLink;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Create a link (wikilink) between two entities in a campaign. Links represent relationships '.
    'like a Character belonging to a Faction, a Location contained in another Location, or a '.
    'Session referencing an Item. from_type may be Character/Faction/Location/Item/Moment/GameSession/'.
    'Beat/Backstory/Journal/World; to_type must be Character/Faction/Location/Item/Moment. The '.
    'alias is the display text that gets wrapped as [[alias]] in the source record\'s text. If a '.
    'link already exists for the same source type, source ID, and normalized alias, this replaces its target.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class CreateLinkTool extends Tool
{
    protected function action(): CreateLink
    {
        return CreateLink::make();
    }
}
