<?php

namespace App\Data;

class CampaignDataShort extends ArchivistDto
{
    public static function rules(): array
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
            'can_manage' => ['required', 'boolean'],
            'created_at' => ['required', 'string', 'date'],
        ];
    }
}
