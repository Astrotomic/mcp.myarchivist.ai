<?php

namespace App\Actions\Archivist\HeroAwards;

use App\Actions\Archivist\ApiAction;
use App\Data\HeroAwardData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class GetHeroAward extends ApiAction
{
    public static function rules(): array
    {
        return [
            'hero_award_id' => ['required', 'string'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get("/v1/hero-awards/{$input->string('hero_award_id')}");
    }

    protected function map(array $data): HeroAwardData
    {
        return new HeroAwardData($data);
    }
}
