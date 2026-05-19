<?php

namespace App\Mcp\Data;

class FactionData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'created_at' => ['required', 'string'],
        ];
    }

    #[\Override]
    protected function descriptions(): array
    {
        return [
            'id' => 'Faction ID.',
            'campaign_id' => 'Campaign ID.',
            'name' => 'Faction name.',
            'created_at' => 'Faction creation timestamp.',
        ];
    }
}
