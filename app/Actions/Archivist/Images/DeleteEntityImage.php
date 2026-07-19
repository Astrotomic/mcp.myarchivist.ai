<?php

namespace App\Actions\Archivist\Images;

use App\Actions\Archivist\WriteApiAction;
use App\Data\ImageDeleteResultData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class DeleteEntityImage extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'entity_type' => ['nullable', 'string', 'in:campaign,world,character,faction,location,item,moment,session,gamesession'],
            'entity_id' => ['nullable', 'string'],
            'image_url' => ['nullable', 'string', 'max:2048'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        $campaignId = $input->string('campaign_id')->toString();

        $body = collect($input->all())
            ->except('campaign_id')
            ->all();

        return $this->client->deleteJson("/v1/campaigns/{$campaignId}/images", $body);
    }

    protected function map(array $data): ImageDeleteResultData
    {
        return new ImageDeleteResultData([
            'removed' => (bool) ($data['removed'] ?? false),
        ]);
    }
}
