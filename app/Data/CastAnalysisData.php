<?php

namespace App\Data;

class CastAnalysisData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'session_id' => ['required', 'string'],
            'analysis' => ['required', 'array'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
