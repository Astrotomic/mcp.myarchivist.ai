<?php

namespace App\Data;

class TranscriptData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'version' => ['required', 'int'],
            'metadata' => ['required', 'array'],
            'utterances' => ['required', 'array'],
            'stats' => ['required', 'array'],
            'text' => ['required', 'string'],
            'created_at' => ['required', 'string', 'date'],
        ];
    }
}
