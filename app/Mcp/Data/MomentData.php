<?php

namespace App\Mcp\Data;

class MomentData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'session_id' => ['nullable', 'string'],
            'label' => ['required', 'string'],
            'content' => ['nullable', 'string'],
            'created_at' => ['required', 'string'],
        ];
    }

    #[\Override]
    protected function descriptions(): array
    {
        return [
            'id' => 'Moment ID.',
            'campaign_id' => 'Campaign ID.',
            'label' => 'Moment label.',
            'created_at' => 'Moment creation timestamp.',
        ];
    }
}
