<?php

namespace App\Mcp\Data;

class CampaignStatsData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'campaignId' => ['required', 'string'],
            'characters' => ['required', 'integer'],
            'sessions' => ['required', 'integer'],
            'moments' => ['required', 'integer'],
            'beats' => ['required', 'integer'],
            'factions' => ['required', 'integer'],
            'locations' => ['required', 'integer'],
            'items' => ['required', 'integer'],
        ];
    }
}
