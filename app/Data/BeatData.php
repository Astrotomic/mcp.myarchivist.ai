<?php

namespace App\Data;

class BeatData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'game_session_id' => ['nullable', 'string'],
            'game_session_ids' => ['nullable', 'list'],
            'label' => ['required', 'string'],
            'type' => ['required', 'string', 'in:major,minor,step'],
            'index' => ['required', 'integer'],
            'parent_id' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'metadata' => ['nullable', 'array'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
