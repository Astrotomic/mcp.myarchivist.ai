<?php

namespace App\Data;

class QuestData extends QuestDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
            'success_definition' => ['nullable', 'string'],
            'failure_conditions' => ['nullable', 'string'],
            'objectives' => ['nullable', 'array'],
            'progress_log' => ['nullable', 'array'],
            'progress_log_entries' => ['nullable', 'array'],
            'related_characters' => ['nullable', 'array'],
            'related_factions' => ['nullable', 'array'],
            'related_locations' => ['nullable', 'array'],
            'related_items' => ['nullable', 'array'],
            'related_entity_refs' => ['nullable', 'array'],
        ]);
    }
}
