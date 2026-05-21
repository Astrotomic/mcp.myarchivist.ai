<?php

namespace App\Actions\Archivist\Moments;

use App\Actions\Archivist\ApiAction;
use App\Data\MomentData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetMoment extends ApiAction
{
    public static function rules(): array
    {
        return [
            'moment_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/moments/{$input->string('moment_id')}", $input->except('moment_id'));
    }

    protected function map(array $data): MomentData
    {
        return new MomentData($data);
    }
}
