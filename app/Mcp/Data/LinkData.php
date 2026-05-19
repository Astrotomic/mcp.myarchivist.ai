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

    #[\Override]
    public function descriptions(): array
    {
        return [
            'id' => 'Link ID.',
            'campaign_id' => 'Campaign ID.',
            'from_id' => 'Source entity ID.',
            'from_type' => 'Source entity type.',
            'to_id' => 'Target entity ID.',
            'to_type' => 'Target entity type.',
            'created_at' => 'Link creation timestamp.',
        ];
    }
}
