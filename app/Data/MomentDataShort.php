<?php

namespace App\Data;

class MomentDataShort extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'session_id' => ['required', 'string'],
            'label' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'index' => ['nullable', 'integer'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
