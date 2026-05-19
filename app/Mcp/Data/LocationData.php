<?php

namespace App\Mcp\Data;

class LocationData extends ArchivistDto
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
            'parent_id' => ['nullable', 'string'],
            'created_at' => ['required', 'string'],
        ];
    }

    #[\Override]
    public function descriptions(): array
    {
        return [
            'id' => 'Location ID.',
            'campaign_id' => 'Campaign ID.',
            'name' => 'Location name.',
            'created_at' => 'Location creation timestamp.',
        ];
    }
}
