<?php

namespace App\Data;

use Illuminate\Support\Arr;

class CampaignData extends CampaignDataShort
{
    public static function rules(): array
    {
        // Campaign detail responses don't carry the list-only viewer_role.
        return array_merge(Arr::except(parent::rules(), ['viewer_role']), [
            'summary' => ['nullable', 'string'],
            'language' => ['nullable', 'string'],
        ]);
    }
}
