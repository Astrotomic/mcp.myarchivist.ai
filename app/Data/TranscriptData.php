<?php

namespace App\Mcp\Data;

class TranscriptData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'version' => ['nullable', 'integer'],
            'metadata' => ['nullable', 'array'],
            'fullText' => ['nullable', 'string'],
            'utterances' => ['nullable', 'array'],
            'stats' => ['nullable', 'array'],
        ];
    }
}
