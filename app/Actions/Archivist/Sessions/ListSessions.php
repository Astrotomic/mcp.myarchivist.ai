<?php

namespace App\Actions\Archivist\Sessions;

use App\Actions\Archivist\ApiAction;
use App\Collections\ArchivistDtoCollection;
use App\Data\SessionDataShort;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

final readonly class ListSessions extends ApiAction
{
    public static function rules(): array
    {
        return array_merge(self::paginationRules(), [
            'campaign_id' => ['required', 'string'],
            'session_type' => ['nullable', 'string', 'in:audioUpload,playByPost,discordVoice,txtUpload,rawNotes,other'],
            'public_only' => ['nullable', 'boolean'],
        ]);
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->get('/v1/sessions', $input->all());
    }

    /**
     * @return ArchivistDtoCollection<int, SessionDataShort>
     */
    protected function map(array $data): ArchivistDtoCollection
    {
        return ArchivistDtoCollection::make(
            collect($data['data'] ?? [])->map(fn (array $item) => new SessionDataShort($item))
        );
    }
}
