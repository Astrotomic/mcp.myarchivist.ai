<?php

namespace App\Actions\Archivist\Characters;

use App\Actions\Archivist\WriteApiAction;
use App\Data\CharacterArcRunReviewData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ReviewCharacterArcRun extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'character_id' => ['required', 'string'],
            'run_id' => ['required', 'string'],
            'action' => ['required', 'string', 'in:approve,decline'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post(
            "/v1/characters/{$input->string('character_id')}/arc/runs/{$input->string('run_id')}/review",
            ['action' => $input->string('action')->toString()],
        );
    }

    protected function map(array $data): CharacterArcRunReviewData
    {
        return new CharacterArcRunReviewData($data);
    }
}
