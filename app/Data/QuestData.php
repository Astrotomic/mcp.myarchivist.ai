<?php

namespace App\Data;

class QuestData extends QuestDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
            'success_definition' => ['nullable', 'string'],
            'failure_conditions' => ['nullable', 'string'],
            'objectives' => ['nullable', 'list'],
            'progress_log' => ['nullable', 'list'],
            'progress_log_entries' => ['nullable', 'list'],
            'related_characters' => ['nullable', 'list'],
            'related_factions' => ['nullable', 'list'],
            'related_locations' => ['nullable', 'list'],
            'related_items' => ['nullable', 'list'],
            'related_entity_refs' => ['nullable', 'list'],
        ]);
    }
}
