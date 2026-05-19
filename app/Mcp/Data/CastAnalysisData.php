<?php

namespace App\Mcp\Data;

class CastAnalysisData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'session_id' => ['required', 'string'],
            'analysis' => ['required', 'array'],
            'created_at' => ['required', 'string'],
            'updated_at' => ['required', 'string'],
        ];
    }

    #[\Override]
    public function descriptions(): array
    {
        return [
            'id' => 'Cast analysis ID.',
            'session_id' => 'Session ID.',
            'analysis' => 'Cast analysis metrics and breakdowns.',
            'created_at' => 'Creation timestamp.',
            'updated_at' => 'Last update timestamp.',
        ];
    }
}
