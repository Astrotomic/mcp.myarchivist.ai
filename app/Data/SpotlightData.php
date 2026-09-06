<?php

namespace App\Data;

class SpotlightData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'session_id' => ['required', 'string'],
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'character_name' => ['nullable', 'string'],
            'player_name' => ['nullable', 'string'],
            'style' => ['nullable', 'string'],
            'custom_theme' => ['nullable', 'array'],
            'order_index' => ['nullable', 'integer'],
            'entry_type' => ['nullable', 'string'],
            'source_moment_id' => ['nullable', 'string'],
            'pipeline_id' => ['nullable', 'string'],
            'recap_set_id' => ['nullable', 'string'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
