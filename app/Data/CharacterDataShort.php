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
            'character_alias' => ['nullable', 'array'],
            'player_name' => ['nullable', 'string'],
            'player' => ['nullable', 'array'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:PC,NPC'],
            'approved' => ['required', 'boolean'],
            'discovered' => ['required', 'boolean'],
        ];
    }
}
