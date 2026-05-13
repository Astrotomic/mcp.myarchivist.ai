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
}
