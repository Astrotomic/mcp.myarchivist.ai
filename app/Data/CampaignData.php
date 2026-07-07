<?php

namespace App\Data;

class CampaignData extends CampaignDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
            'summary' => ['nullable', 'string'],
            'language' => ['nullable', 'string'],
            'archived' => ['nullable', 'boolean'],
            'archived_at' => ['nullable', 'string'],
        ]);
    }
}
