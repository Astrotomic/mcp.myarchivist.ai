<?php

namespace App\Data;

class CharacterDataShort extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'character_name' => ['required', 'string'],
            'character_alias' => ['nullable', 'string'],
            'character_aliases' => ['nullable', 'array'],
            'player_name' => ['nullable', 'string'],
            'player_handle' => ['nullable', 'string'],
            'player' => ['nullable', 'array'],
            'description' => ['nullable', 'string'],
            'backstory' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'tcg_image' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:PC,NPC'],
            'merge' => ['required', 'boolean'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
