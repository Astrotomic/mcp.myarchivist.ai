<?php

namespace App\Mcp\Data;

class BeatData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
            'campaign_id' => ['required', 'string'],
            'game_session_id' => ['nullable', 'string'],
            'label' => ['required', 'string'],
            'type' => ['required', 'string', 'in:major,minor,step'],
            'description' => ['nullable', 'string'],
            'index' => ['required', 'integer'],
            'parent_id' => ['nullable', 'string'],
            'created_at' => ['required', 'string'],
        ];
    }

    #[\Override]
    protected function descriptions(): array
    {
        return [
            'id' => 'Beat ID.',
            'campaign_id' => 'Campaign ID.',
            'game_session_id' => 'Session ID.',
            'label' => 'Beat label.',
            'type' => 'Beat type.',
            'description' => 'Beat description.',
            'index' => 'Beat ordering index.',
            'parent_id' => 'Parent beat ID.',
            'created_at' => 'Beat creation timestamp.',
        ];
    }
}
