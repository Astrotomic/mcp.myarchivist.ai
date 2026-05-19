<?php

namespace App\Data;

class CharacterData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'character_name' => ['required', 'string'],
            'character_alias' => ['nullable', 'array'],
            'player_name' => ['nullable', 'string'],
            'player' => ['nullable', 'array'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:PC,NPC'],
            'character_aliases' => ['nullable', 'array'],
            'player_handle' => ['nullable', 'string'],
            'backstory' => ['nullable', 'string'],
            'tcg_image' => ['nullable', 'string'],
            'merge' => ['required', 'bool'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
