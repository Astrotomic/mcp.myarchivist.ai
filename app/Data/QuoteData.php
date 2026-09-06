<?php

namespace App\Data;

class QuoteData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'session_id' => ['required', 'string'],
            'text' => ['required', 'string'],
            'speaker' => ['nullable', 'string'],
            'character_name' => ['nullable', 'string'],
            'context' => ['nullable', 'string'],
            'style' => ['nullable', 'string'],
            'custom_theme' => ['nullable', 'array'],
            'order_index' => ['nullable', 'integer'],
            'pipeline_id' => ['nullable', 'string'],
            'recap_set_id' => ['nullable', 'string'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
