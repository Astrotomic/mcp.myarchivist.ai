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
        // The API's campaign stats endpoint counts raw Character rows, including
        // review-stage records that agent/MCP callers cannot list. Use the same
        // list endpoint exposed by MCP so "characters" means visible characters.
        if (! empty($data['campaign_id'])) {
            $charactersResponse = $this->client->get('/v1/characters', [
                'campaign_id' => $data['campaign_id'],
                'page' => 1,
                'size' => 1,
            ]);

            if ($charactersResponse->successful()) {
                $data['characters'] = $charactersResponse->fluent()->integer(
                    'total',
                    (int) ($data['characters'] ?? 0),
                );
            }
        }

        return new CampaignStatsData($data);
    }
}
