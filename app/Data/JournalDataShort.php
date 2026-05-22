<?php

namespace App\Data;

class JournalDataShort extends ArchivistDto
{
    public static function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'title' => ['required', 'string'],
            'summary' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:draft,published,archived'],
            'folder_id' => ['nullable', 'string'],
            'is_public' => ['required', 'boolean'],
            'is_pinned' => ['nullable', 'boolean'],
            'tags' => ['nullable', 'list'],
            'author_id' => ['nullable', 'string'],
            'last_edited_by_id' => ['nullable', 'string'],
            'token_count' => ['nullable', 'integer'],
            'cover_image' => ['nullable', 'string'],
            'published_at' => ['nullable', 'string'],
            'archived_at' => ['nullable', 'string'],
            'created_at' => ['required', 'string', 'date'],
            'updated_at' => ['nullable', 'string', 'date'],
        ];
    }
}
