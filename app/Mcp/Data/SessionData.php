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
            'notes' => ['nullable', 'string'],
            'session_date' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'index' => ['nullable', 'integer'],
            'pending' => ['nullable', 'boolean'],
            'public' => ['required', 'boolean'],
            'created_at' => ['nullable', 'string'],
            'updated_at' => ['nullable', 'string'],
            // Detail-only fields (from GameSessionDetail)
            'pbp_start_msg_url' => ['nullable', 'string'],
            'pbp_end_msg_url' => ['nullable', 'string'],
            'dg_request_id' => ['nullable', 'string'],
            'beats' => ['nullable', 'array'],
            'moments' => ['nullable', 'array'],
        ];
    }
}
