<?php

namespace App\Actions\Archivist\Images;

use App\Actions\Archivist\WriteApiAction;
use App\Data\ImageGenerateResultData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GenerateImage extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'type' => ['required', 'string', 'in:character,faction,location,item,world'],
            'entity_id' => ['nullable', 'string'],
            'user_input' => ['nullable', 'string', 'max:20000'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post('/v1/images/generate', $input->all());
    }

    protected function map(array $data): ImageGenerateResultData
    {
        return new ImageGenerateResultData($data);
    }
}
