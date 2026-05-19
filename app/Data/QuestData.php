<?php

namespace App\Data;

class QuestData extends QuestDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['required', 'string', 'date'],
            'quest_giver' => ['nullable', 'string'],
            'quest_giver_id' => ['nullable', 'string'],
            'success_definition' => ['nullable', 'string'],
            'failure_conditions' => ['nullable', 'string'],
            'next_action' => ['nullable', 'string'],
            'resolution' => ['nullable', 'string'],
            'objectives' => ['nullable', 'array'],
            'progress_log' => ['nullable', 'array'],
            'progress_log_entries' => ['nullable', 'array'],
            'related_characters' => ['nullable', 'array'],
            'related_factions' => ['nullable', 'array'],
            'related_locations' => ['nullable', 'array'],
            'related_items' => ['nullable', 'array'],
            'related_entity_refs' => ['nullable', 'array'],
            'objective_count' => ['nullable', 'integer'],
            'completed_objective_count' => ['nullable', 'integer'],
            'progress_entry_count' => ['nullable', 'integer'],
            'related_entity_count' => ['nullable', 'integer'],
            'first_session' => ['nullable', 'array'],
            'last_session' => ['nullable', 'array'],
        ]);
    }
}
