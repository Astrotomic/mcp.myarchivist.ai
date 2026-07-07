<?php

namespace App\Actions\Archivist\Moments;

use App\Actions\Archivist\WriteApiAction;
use App\Data\MomentData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class UpdateMoment extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'moment_id' => ['required', 'string'],
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
        return $this->client->patch(
            "/v1/moments/{$input->string('moment_id')}",
            $input->except('moment_id'),
        );
    }

    protected function map(array $data): MomentData
    {
        return new MomentData($data);
    }
}
