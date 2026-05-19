<?php

namespace App\Data;

class CharacterData extends CharacterDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
            'character_aliases' => ['nullable', 'array'],
            'shadow_aliases' => ['nullable', 'array'],
            'backstory' => ['nullable', 'string'],
            'player_handle' => ['nullable', 'string'],
            'new_description' => ['nullable', 'string'],
            'old_description' => ['nullable', 'string'],
            'combined_description' => ['nullable', 'string'],
            'tcg_image' => ['nullable', 'string'],
            'context' => ['nullable', 'array'],
            'pending' => ['required', 'bool'],
            'merge' => ['required', 'bool'],
            'match_info' => ['nullable', 'array'],
            'speaker_id' => ['nullable', 'string'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ]);
    }
}
