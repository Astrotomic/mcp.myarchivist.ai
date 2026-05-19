<?php

namespace App\Data;

class LinkData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'from_id' => ['required', 'string'],
            'from_type' => ['required', 'string'],
            'to_id' => ['required', 'string'],
            'to_type' => ['required', 'string'],
            'alias' => ['nullable', 'string'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
