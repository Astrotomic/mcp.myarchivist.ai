<?php

namespace App\Actions\Archivist\Images;

use App\Actions\Archivist\WriteApiAction;
use App\Data\ImageUploadCompleteData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class CompleteImageUpload extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'object_key' => ['required', 'string', 'min:1', 'max:2048'],
            'entity_type' => ['required', 'string', 'in:campaign,world,character,faction,location,item,moment,session,gamesession'],
            'entity_id' => ['required', 'string'],
            'attach' => ['nullable', 'boolean'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        $campaignId = $input->string('campaign_id')->toString();

        $body = collect($input->all())
            ->except('campaign_id')
            ->all();

        return $this->client->post("/v1/campaigns/{$campaignId}/images/complete", $body);
    }

    protected function map(array $data): ImageUploadCompleteData
    {
        return new ImageUploadCompleteData([
            'url' => (string) ($data['url'] ?? ''),
            'attached' => (bool) ($data['attached'] ?? false),
        ]);
    }
}
