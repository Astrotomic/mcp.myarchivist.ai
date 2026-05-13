<?php

namespace App\Mcp\Data;

class SessionData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'type' => ['nullable', 'string'],
            'title' => ['nullable', 'string'],
            'summary' => ['nullable', 'string'],
            'session_date' => ['nullable', 'string'],
            'public' => ['required', 'boolean'],
            'created_at' => ['required', 'string'],
        ];
    }
}
