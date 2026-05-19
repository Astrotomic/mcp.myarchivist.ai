<?php

namespace App\Data;

class CampaignData extends CampaignDataShort
{
    public static function rules(): array
    {
        return array_merge(parent::rules(), [
            'summary' => ['nullable', 'string'],
            'language' => ['nullable', 'string'],
            'chat_tone' => ['nullable', 'string'],
            'ai_image_gen' => ['nullable', 'boolean'],
            'new' => ['nullable', 'boolean'],
            'archived' => ['required', 'boolean'],
            'archived_at' => ['nullable', 'string', 'date'],
            'bot_active' => ['required', 'boolean'],
            'flagged' => ['required', 'boolean'],
            'indexed' => ['required', 'boolean'],
            'players' => ['nullable', 'array'],
            'keywords' => ['nullable', 'array'],
            'kill_list' => ['nullable', 'array'],
            'updated_at' => ['nullable', 'string', 'date'],
        ]);
    }
}
