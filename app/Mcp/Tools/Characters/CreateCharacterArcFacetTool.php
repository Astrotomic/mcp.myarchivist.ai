<?php

namespace App\Mcp\Tools\Characters;

use App\Actions\Archivist\Characters\CreateCharacterArcFacet;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Add an approved entry to a player character\'s Character Arc under one theme '.
    '(CharacterDevelopment, Relationships, CombatCapability, KnowledgeDiscovery, '.
    'ReputationAffiliation, ItemsResources, ConditionsConsequences). Pass session_id to tag the session '.
    'it happened in. PCs only. If a current facet with the same title already exists in the theme, '.
    'the API returns 409: update that facet instead.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class CreateCharacterArcFacetTool extends Tool
{
    protected function action(): CreateCharacterArcFacet
    {
        return CreateCharacterArcFacet::make();
    }
}
