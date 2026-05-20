<?php

namespace App\Data;

class SessionDataShort extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'type' => ['nullable', 'string', 'in:audioUpload,playByPost,discordVoice,rawNotes'],
            'title' => ['nullable', 'string'],
            'summary' => ['nullable', 'string'],
            'session_date' => ['nullable', 'string'],
            'public' => ['required', 'boolean'],
            'notes' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'index' => ['nullable', 'integer'],
            'pbp_start_msg_url' => ['nullable', 'string'],
            'pbp_end_msg_url' => ['nullable', 'string'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
