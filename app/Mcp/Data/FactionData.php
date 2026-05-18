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
            'aliases' => ['nullable', 'array'],
            'description' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'tcg_image' => ['nullable', 'string'],
            'approved' => ['nullable', 'boolean'],
            'discovered' => ['nullable', 'boolean'],
            'created_at' => ['nullable', 'string'],
            'updated_at' => ['nullable', 'string'],
        ];
    }
}
