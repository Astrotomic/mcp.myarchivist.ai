<?php

namespace App\Mcp\Data;

class CampaignData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'system' => ['nullable', 'string'],
            'public' => ['required', 'boolean'],
            'created_at' => ['required', 'string'],
        ];
    }

    #[\Override]
    protected function descriptions(): array
    {
        return [
            'id' => 'Campaign ID.',
            'title' => 'Campaign title.',
            'public' => 'Whether the campaign is public.',
            'created_at' => 'Campaign creation timestamp.',
        ];
    }
}
