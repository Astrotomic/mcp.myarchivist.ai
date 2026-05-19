<?php

namespace App\Mcp\Data;

class ItemData extends ArchivistDto
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
            'id' => 'Item ID.',
            'campaign_id' => 'Campaign ID.',
            'name' => 'Item name.',
            'created_at' => 'Item creation timestamp.',
        ];
    }
}
