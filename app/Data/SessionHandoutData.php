<?php

namespace App\Mcp\Data;

class SessionHandoutData extends ArchivistDto
{
    #[\Override]
    public function rules(): array
    {
        return [
            'summary' => ['required', 'string'],
            'sessionOutline' => ['nullable', 'string'],
            'encounters' => ['nullable', 'array'],
            'characterSpotlight' => ['nullable', 'array'],
            'otherEntitySpotlight' => ['nullable', 'array'],
            'items' => ['nullable', 'array'],
            'valuableInformation' => ['nullable', 'array'],
            'partyStatusAndNextSteps' => ['nullable', 'array'],
            'moments' => ['nullable', 'array'],
        ];
    }
}
