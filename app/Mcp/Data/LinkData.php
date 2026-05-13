<?php

namespace App\Mcp\Data;

class LinkData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'from_id' => ['required', 'string'],
            'from_type' => ['required', 'string'],
            'to_id' => ['required', 'string'],
            'to_type' => ['required', 'string'],
            'alias' => ['nullable', 'string'],
            'created_at' => ['required', 'string'],
        ];
    }
}
