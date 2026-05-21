<?php

namespace App\Data;

class MomentDataShort extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'session_id' => ['nullable', 'string'],
            'label' => ['nullable', 'string'],
            'index' => ['nullable', 'integer'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'categories' => ['nullable', 'array'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
