<?php

namespace App\Data;

class SessionDataShort extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'type' => ['nullable', 'string', 'in:audioUpload,playByPost,discordVoice,txtUpload,rawNotes,other'],
            'title' => ['nullable', 'string'],
            'summary' => ['nullable', 'string'],
            'session_date' => ['nullable', 'string'],
            'public' => ['required', 'boolean'],
            'notes' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'index' => ['nullable', 'integer'],
            'pbp_start_msg_url' => ['nullable', 'string'],
            'pbp_end_msg_url' => ['nullable', 'string'],
            'ai_session_review' => ['nullable', 'array'],
            'ai_session_review.stage' => ['nullable', 'string'],
            'ai_session_review.review_started_at' => ['nullable', 'string', 'date'],
            'ai_session_review.save_started_at' => ['nullable', 'string', 'date'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
