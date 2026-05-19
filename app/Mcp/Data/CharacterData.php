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
            'character_aliases' => ['nullable', 'array'],
            'character_aliases.*' => ['string'],
            'player_name' => ['nullable', 'string'],
            'player' => ['nullable', 'array'],
            'description' => ['nullable', 'string'],
            'backstory' => ['nullable', 'string'],
            'speaker_id' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'approved' => ['required', 'boolean'],
            'created_at' => ['required', 'string'],
        ];
    }

    #[\Override]
    protected function descriptions(): array
    {
        return [
            'id' => 'Character ID.',
            'campaign_id' => 'Campaign ID.',
            'character_name' => 'Character name.',
            'approved' => 'Whether the character is approved.',
            'created_at' => 'Character creation timestamp.',
        ];
    }
}
