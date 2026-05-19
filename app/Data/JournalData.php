<?php

namespace App\Data;

class JournalData extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'world_id' => ['required', 'string'],
            'title' => ['required', 'string'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'content_rich' => ['nullable', 'array'],
            'content_metadata' => ['nullable', 'array'],
            'tags' => ['nullable', 'array'],
            'token_count' => ['nullable', 'integer'],
            'is_public' => ['required', 'boolean'],
            'is_pinned' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'in:draft,published,archived'],
            'folder_id' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string'],
            'permission_level' => ['nullable', 'string'],
            'published_at' => ['nullable', 'string'],
            'archived_at' => ['nullable', 'string'],
            'created_at' => ['required', 'string', 'date'],
        ];
    }
}
