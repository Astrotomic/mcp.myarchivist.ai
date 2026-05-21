<?php

namespace App\Data;

class SessionTranscriptData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'version' => ['required', 'integer'],
            'metadata' => ['required', 'array'],
            'text' => ['required', 'string'],
            'utterances' => ['required', 'array'],
            'stats' => ['required', 'array'],
            'created_at' => ['required', 'string', 'date'],
        ];
    }
}
