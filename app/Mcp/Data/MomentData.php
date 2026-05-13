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
            'label' => ['required', 'string'],
            'content' => ['nullable', 'string'],
            'created_at' => ['required', 'string'],
        ];
    }
}
