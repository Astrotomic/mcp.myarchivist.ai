<?php

namespace App\Mcp\Data;

class MomentData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'session_id' => ['nullable', 'string'],
            'label' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'index' => ['nullable', 'integer'],
            'categories' => ['nullable', 'array'],
            'pending' => ['nullable', 'boolean'],
            'approved' => ['nullable', 'boolean'],
            'discovered' => ['nullable', 'boolean'],
            'created_at' => ['nullable', 'string'],
            'updated_at' => ['nullable', 'string'],
        ];
    }
}
