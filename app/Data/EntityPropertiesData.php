<?php

namespace App\Data;

class EntityPropertiesData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'entity_id' => ['required', 'string'],
            'entity_type' => ['required', 'string'],
            'compendium_entity_type' => ['nullable', 'string'],
            // Each table: {table_type, label, fields: [{id, key, label, description, field_type,
            // is_required, sort_order, options, connection_types, value, connections, updated_at}]}.
            'tables' => ['required', 'list'],
        ];
    }
}
