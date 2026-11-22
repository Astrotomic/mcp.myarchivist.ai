<?php

namespace App\Data;

class TextSessionData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'session_id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'title' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'session_date' => ['nullable', 'string'],
            'review_stage' => ['nullable', 'string'],
            'replayed' => ['required', 'boolean'],
            'url' => ['required', 'string'],
        ];
    }
}
