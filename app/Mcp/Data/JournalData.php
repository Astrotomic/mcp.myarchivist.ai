<?php

namespace App\Mcp\Data;

class JournalData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
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
            'created_at' => ['required', 'string'],
            'updated_at' => ['required', 'string'],
        ];
    }

    #[\Override]
    protected function descriptions(): array
    {
        return [
            'id' => 'Journal entry ID.',
            'world_id' => 'World ID.',
            'title' => 'Journal title.',
            'is_public' => 'Whether the journal entry is public.',
            'created_at' => 'Journal creation timestamp.',
            'updated_at' => 'Last update timestamp.',
        ];
    }
}
