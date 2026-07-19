<?php

namespace App\Actions\Archivist\Moments;

use App\Actions\Archivist\WriteApiAction;
use App\Data\MomentData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class CreateMoment extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'session_id' => ['required', 'string'],
            'label' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:20000'],
            'image' => ['nullable', 'string'],
            'index' => ['nullable', 'integer'],
            'categories' => ['nullable', 'list'],
            'pending' => ['nullable', 'boolean'],
            'approved' => ['nullable', 'boolean'],
            'discovered' => ['nullable', 'boolean'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post('/v1/moments', $input->all());
    }

    protected function map(array $data): MomentData
    {
        return new MomentData($data);
    }
}
