<?php

namespace App\Actions\Archivist\Images;

use App\Actions\Archivist\ApiAction;
use App\Data\ImageUsageData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetImageUsage extends ApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/images/usage', [
            'campaign_id' => $input->string('campaign_id')->toString(),
        ]);
    }

    protected function map(array $data): ImageUsageData
    {
        return new ImageUsageData($data);
    }
}
