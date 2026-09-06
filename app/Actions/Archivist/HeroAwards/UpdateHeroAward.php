<?php

namespace App\Actions\Archivist\HeroAwards;

use App\Actions\Archivist\WriteApiAction;
use App\Data\HeroAwardData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class UpdateHeroAward extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'hero_award_id' => ['required', 'string'],
            'title' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:4000'],
            'character_name' => ['nullable', 'string', 'max:120'],
            'player_name' => ['nullable', 'string', 'max:120'],
            'tone_mode' => ['nullable', 'string', 'max:40'],
            'award_mode' => ['nullable', 'string', 'in:moment,theme'],
            'style' => ['nullable', 'string', 'max:40'],
            'variant' => ['nullable', 'string', 'max:40'],
            'custom_theme' => ['nullable', 'array'],
            'order_index' => ['nullable', 'integer'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->patch(
            "/v1/hero-awards/{$input->string('hero_award_id')}",
            $input->except('hero_award_id'),
        );
    }

    protected function map(array $data): HeroAwardData
    {
        return new HeroAwardData($data);
    }
}
