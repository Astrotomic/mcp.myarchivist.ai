<?php

namespace App\Data;

class JournalFolderData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'parent_id' => ['nullable', 'string'],
            'name' => ['required', 'string'],
            'path' => ['required', 'string'],
            'position' => ['required', 'integer'],
            'description' => ['nullable', 'string'],
            'metadata' => ['nullable', 'list'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
