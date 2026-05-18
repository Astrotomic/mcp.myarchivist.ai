<?php

namespace App\Mcp\Data;

class CampaignStatsData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'world_id' => ['required', 'string'],
            'title' => ['required', 'string'],
            'characters' => ['required', 'integer'],
            'sessions' => ['required', 'integer'],
            'moments' => ['required', 'integer'],
            'players' => ['nullable', 'integer'],
            'admins' => ['nullable', 'integer'],
            'public' => ['nullable', 'boolean'],
            'created_at' => ['nullable', 'string'],
        ];
    }
}
