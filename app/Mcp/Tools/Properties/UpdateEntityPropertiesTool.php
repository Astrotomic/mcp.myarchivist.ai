<?php

namespace App\Mcp\Tools\Properties;

use App\Actions\Archivist\Properties\UpdateEntityProperties;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'Partially update the structured Properties of a character, faction, location or item. '.
    'properties is an object of {field_key: value}; only the keys you send change. Read the entity '.
    'first with with_properties: true to see its fields (key, field_type, options, connection_types, '.
    'current value and connections). Values: text/ENUM fields take a string (null clears; ENUM matches '.
    'options case-insensitively); NUMBER a number; BOOLEAN true/false; connection fields (non-empty '.
    'connection_types, e.g. allies, memberships, signature_item) take a list of entity ids or '.
    'exact names that REPLACES the list, [] to clear, or {"add": [...], "remove": [...]} to edit it. '.
    'Unknown keys or entities that do not resolve in the campaign are rejected (nothing is written). '.
    'Returns the updated property tables. Note: PCs have Identity and Connections tables; NPCs, '.
    'creatures, factions, locations and items have a General table.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class UpdateEntityPropertiesTool extends Tool
{
    protected function action(): UpdateEntityProperties
    {
        return UpdateEntityProperties::make();
    }
}
