<?php

namespace App\Actions\Archivist\Beats;

use App\Actions\Archivist\WriteApiAction;
use App\Data\BeatData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class CreateBeat extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'label' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'type' => ['nullable', 'string', 'in:major,minor,step'],
            'index' => ['nullable', 'integer'],
            'parent_id' => ['nullable', 'string'],
            'game_session_id' => ['nullable', 'string'],
            'game_session_ids' => ['nullable', 'list'],
            'metadata' => ['nullable', 'array'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post('/v1/beats', $input->all());
    }

    protected function map(array $data): BeatData
    {
        return new BeatData($data);
    }
}
