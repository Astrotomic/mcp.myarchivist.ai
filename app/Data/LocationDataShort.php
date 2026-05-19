<?php

namespace App\Data;

class LocationDataShort extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'approved' => ['required', 'boolean'],
            'discovered' => ['required', 'boolean'],
        ];
    }
}
