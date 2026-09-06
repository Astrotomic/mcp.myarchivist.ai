<?php

namespace App\Data;

class HeroAwardData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'session_id' => ['required', 'string'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'character_name' => ['nullable', 'string'],
            'player_name' => ['nullable', 'string'],
            'tone_mode' => ['nullable', 'string'],
            'award_mode' => ['nullable', 'string'],
            'style' => ['nullable', 'string'],
            'variant' => ['nullable', 'string'],
            'custom_theme' => ['nullable', 'array'],
            'order_index' => ['nullable', 'integer'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
