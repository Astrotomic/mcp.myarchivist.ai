<?php

namespace App\Data;

class FactionDataShort extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'aliases' => ['nullable', 'array'],
            'description' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'tcg_image' => ['nullable', 'string'],
            'merge' => ['required', 'boolean'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
