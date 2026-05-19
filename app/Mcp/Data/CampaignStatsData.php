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

    #[\Override]
    public function descriptions(): array
    {
        return [
            'campaignId' => 'Campaign ID.',
            'characters' => 'Character count.',
            'sessions' => 'Session count.',
            'moments' => 'Moment count.',
            'beats' => 'Beat count.',
            'factions' => 'Faction count.',
            'locations' => 'Location count.',
            'items' => 'Item count.',
        ];
    }
}
