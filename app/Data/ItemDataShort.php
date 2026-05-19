<?php

namespace App\Data;

class ItemDataShort extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'approved' => ['required', 'boolean'],
            'discovered' => ['required', 'boolean'],
        ];
    }
}
