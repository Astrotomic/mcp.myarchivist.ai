<?php

namespace App\Mcp\Data;

class JournalFolderData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
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
            'created_at' => ['required', 'string'],
            'updated_at' => ['required', 'string'],
        ];
    }

    #[\Override]
    protected function descriptions(): array
    {
        return [
            'id' => 'Journal folder ID.',
            'world_id' => 'World ID.',
            'name' => 'Folder name.',
            'path' => 'Folder path.',
            'position' => 'Folder position.',
            'created_at' => 'Folder creation timestamp.',
            'updated_at' => 'Last update timestamp.',
        ];
    }
}
