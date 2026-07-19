<?php

namespace App\Actions\Archivist\Images;

use App\Actions\Archivist\WriteApiAction;
use App\Data\ImageUploadInitData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class InitImageUpload extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'entity_type' => ['required', 'string', 'in:campaign,world,character,faction,location,item,moment,session,gamesession'],
            'entity_id' => ['required', 'string'],
            'file_name' => ['required', 'string', 'min:1', 'max:255'],
            'content_type' => ['required', 'string', 'min:1', 'max:100'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        $campaignId = $input->string('campaign_id')->toString();

        $body = collect($input->all())
            ->except('campaign_id')
            ->all();

        return $this->client->post("/v1/campaigns/{$campaignId}/images/init", $body);
    }

    protected function map(array $data): ImageUploadInitData
    {
        return new ImageUploadInitData($data);
    }
}
