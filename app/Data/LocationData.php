<?php

namespace App\Data;

class LocationData extends ArchivistDto
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
            'aliases' => ['nullable', 'list'],
            'tcg_image' => ['nullable', 'string'],
            'merge' => ['nullable', 'bool'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
