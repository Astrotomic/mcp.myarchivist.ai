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
            'pending' => ['required', 'boolean'],
        ];
    }
}
