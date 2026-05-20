<?php

namespace App\Data;

class BeatDataShort extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'game_session_id' => ['nullable', 'string'],
            'game_session_ids' => ['nullable', 'array'],
            'label' => ['required', 'string'],
            'type' => ['required', 'string', 'in:major,minor,step'],
            'index' => ['required', 'integer'],
            'parent_id' => ['nullable', 'string'],
        ];
    }
}
