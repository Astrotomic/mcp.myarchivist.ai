<?php

namespace App\Actions\Archivist\Campaigns;

use App\Actions\Archivist\WriteApiAction;
use App\Data\CampaignData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class CreateCampaign extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'system' => ['nullable', 'string'],
            'summary' => ['nullable', 'string'],
            'language' => ['nullable', 'string'],
            'chat_tone' => ['nullable', 'string'],
            'campaign_tone' => ['nullable', 'string'],
            'entities_tone' => ['nullable', 'string'],
            'ai_image_gen' => ['nullable', 'boolean'],
            'public' => ['nullable', 'boolean'],
            'mature' => ['nullable', 'boolean'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post('/v1/campaigns', $input->all());
    }

    protected function map(array $data): CampaignData
    {
        return new CampaignData($data);
    }
}
