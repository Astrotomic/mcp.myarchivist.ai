<?php

namespace App\Actions\Archivist\Campaigns;

use App\Actions\Archivist\ApiAction;
use App\Data\CampaignStatsData;
use App\Exceptions\ArchivistApiException;
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
        // review-stage records that agent/MCP callers cannot list. Prefer the same
        // approved-character total exposed by the MCP character list when the
        // caller has permission for that secondary endpoint. If not, preserve the
        // already-successful stats response rather than failing the whole tool.
        if (! empty($data['campaign_id'])) {
            try {
                $charactersResponse = $this->client->get('/v1/characters', [
                    'campaign_id' => $data['campaign_id'],
                    'page' => 1,
                    'size' => 1,
                ]);

                $data['characters'] = $charactersResponse->fluent()->integer(
                    'total',
                    (int) ($data['characters'] ?? 0),
                );
            } catch (ArchivistApiException) {
                // The secondary lookup is optional. Keep the original stats count
                // when the token lacks characters_read or the endpoint rejects it.
            }
        }

        return new CampaignStatsData($data);
    }
}
