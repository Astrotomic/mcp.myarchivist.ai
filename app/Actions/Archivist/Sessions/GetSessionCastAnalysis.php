<?php

namespace App\Actions\Archivist\Sessions;

use App\Actions\Archivist\ApiAction;
use App\Data\CastAnalysisData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetSessionCastAnalysis extends ApiAction
{
    public static function rules(): array
    {
        return [
            'session_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/sessions/{$input->string('session_id')}/cast-analysis");
    }

    protected function map(array $data): CastAnalysisData
    {
        return new CastAnalysisData($data);
    }
}
