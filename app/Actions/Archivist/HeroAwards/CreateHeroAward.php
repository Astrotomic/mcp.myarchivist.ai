<?php

namespace App\Actions\Archivist\HeroAwards;

use App\Actions\Archivist\WriteApiAction;
use App\Data\HeroAwardData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class CreateHeroAward extends WriteApiAction
{
    public static function rules(): array
    {
        return [
            'session_id' => ['required', 'string'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:4000'],
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
        return $this->client->post('/v1/hero-awards', $input->all());
    }

    protected function map(array $data): HeroAwardData
    {
        return new HeroAwardData($data);
    }
}
