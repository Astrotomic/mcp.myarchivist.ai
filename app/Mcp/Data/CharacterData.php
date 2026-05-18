<?php

namespace App\Mcp\Data;

class CharacterData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'character_name' => ['required', 'string'],
            'character_alias' => ['nullable', 'string'],
            'character_aliases' => ['nullable', 'array'],
            'character_aliases.*' => ['string'],
            'player_name' => ['nullable', 'string'],
            'player_handle' => ['nullable', 'string'],
            'player' => ['nullable', 'array'],
            'description' => ['nullable', 'string'],
            'backstory' => ['nullable', 'string'],
            'speaker_id' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'tcg_image' => ['nullable', 'string'],
            'approved' => ['nullable', 'boolean'],
            'discovered' => ['nullable', 'boolean'],
            'pending' => ['nullable', 'boolean'],
            'merge' => ['nullable', 'boolean'],
            'match_info' => ['nullable', 'array'],
            'shadow_aliases' => ['nullable', 'array'],
            'new_description' => ['nullable', 'string'],
            'old_description' => ['nullable', 'string'],
            'combined_description' => ['nullable', 'string'],
            'context' => ['nullable', 'array'],
            'created_at' => ['nullable', 'string'],
            'updated_at' => ['nullable', 'string'],
        ];
    }
}
