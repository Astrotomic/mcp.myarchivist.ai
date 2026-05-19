<?php

namespace App\Mcp\Data;

class BeatData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'game_session_id' => ['nullable', 'string'],
            'game_session_ids' => ['nullable', 'array'],
            'label' => ['required', 'string'],
            'type' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'index' => ['required', 'integer', 'min:0'],
            'metadata' => ['nullable', 'array'],
            'parent_id' => ['nullable', 'string'],
            'created_at' => ['nullable', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
