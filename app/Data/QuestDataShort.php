<?php

namespace App\Data;

class QuestDataShort extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'order_index' => ['required', 'integer'],
            'quest_name' => ['required', 'string'],
            'quest_category' => ['nullable', 'string', 'in:main,side,faction,personal,n/a'],
            'status' => ['nullable', 'string', 'in:planned,in-progress,blocked,failed,done,n/a'],
            'quest_giver' => ['nullable', 'string'],
            'quest_giver_id' => ['nullable', 'string'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
            'next_action' => ['nullable', 'string'],
            'resolution' => ['nullable', 'string'],
            'objective_count' => ['nullable', 'integer'],
            'completed_objective_count' => ['nullable', 'integer'],
            'progress_entry_count' => ['nullable', 'integer'],
            'related_entity_count' => ['nullable', 'integer'],
            'first_session' => ['nullable', 'array'],
            'last_session' => ['nullable', 'array'],
        ];
    }
}
