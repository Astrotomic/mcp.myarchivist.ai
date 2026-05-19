<?php

namespace App\Data;

class CampaignStatsData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'title' => ['required', 'string'],
            'characters' => ['required', 'integer'],
            'sessions' => ['required', 'integer'],
            'moments' => ['required', 'integer'],
            'public' => ['required', 'bool'],
            'created_at' => ['required', 'string', 'date'],
        ];
    }
}
