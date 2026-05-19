<?php

namespace App\Mcp\Data;

class QuestData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'order_index' => ['required', 'integer'],
            'quest_name' => ['required', 'string'],
            'quest_giver' => ['nullable', 'string'],
            'quest_giver_id' => ['nullable', 'string'],
            'quest_category' => ['nullable', 'string', 'in:main,side,faction,personal,n/a'],
            'status' => ['nullable', 'string', 'in:planned,in-progress,blocked,failed,done,n/a'],
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
            'created_at' => ['required', 'string'],
            'updated_at' => ['required', 'string'],
        ];
    }

    #[\Override]
    protected function descriptions(): array
    {
        return [
            'id' => 'Quest ID.',
            'campaign_id' => 'Campaign ID.',
            'order_index' => 'Quest ordering index.',
            'quest_name' => 'Quest name.',
            'quest_category' => 'Quest category.',
            'status' => 'Quest status.',
            'created_at' => 'Quest creation timestamp.',
            'updated_at' => 'Last update timestamp.',
        ];
    }
}
