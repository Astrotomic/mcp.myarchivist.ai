<?php

namespace App\Actions\Archivist\Campaigns;

use App\Actions\Archivist\ApiAction;
use App\Data\CampaignStatsData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetCampaignStats extends ApiAction
{
    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/campaigns/{$input->string('campaign_id')}/stats");
    }

    protected function map(array $data): CampaignStatsData
    {
        return new CampaignStatsData($data);
    }
}
