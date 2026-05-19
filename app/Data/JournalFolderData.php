<?php

namespace App\Data;

class JournalFolderData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'world_id' => ['required', 'string'],
            'parent_id' => ['nullable', 'string'],
            'name' => ['required', 'string'],
            'path' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'position' => ['required', 'integer'],
            'metadata' => ['nullable', 'array'],
            'created_at' => ['required', 'string', 'date'],
        ];
    }
}
