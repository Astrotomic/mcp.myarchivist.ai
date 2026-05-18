<?php

namespace App\Mcp\Data;

class CampaignData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'system' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'public' => ['required', 'boolean'],
            'mature' => ['required', 'boolean'],
            'owner_id' => ['required', 'string'],
            'can_manage' => ['nullable', 'boolean'],
            'created_at' => ['required', 'string'],
            // Full detail fields (present on GET single campaign)
            'summary' => ['nullable', 'string'],
            'language' => ['nullable', 'string'],
            'chat_tone' => ['nullable', 'string'],
            'ai_image_gen' => ['nullable', 'boolean'],
            'new' => ['nullable', 'boolean'],
            'archived' => ['nullable', 'boolean'],
            'archived_at' => ['nullable', 'string'],
            'bot_active' => ['nullable', 'boolean'],
            'flagged' => ['nullable', 'boolean'],
            'indexed' => ['nullable', 'boolean'],
            'players' => ['nullable', 'array'],
            'keywords' => ['nullable', 'array'],
            'kill_list' => ['nullable', 'array'],
        ];
    }
}
