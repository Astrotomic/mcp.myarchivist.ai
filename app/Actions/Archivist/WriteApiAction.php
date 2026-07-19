<?php

namespace App\Actions\Archivist;

use App\Collections\ArchivistDtoCollection;
use App\Data\ArchivistDto;
use App\Data\SuccessData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\ValidatedInput;

/**
 * Base class for write (POST/PATCH/PUT/DELETE) API actions.
 *
 * When the endpoint responds with an empty body (typically HTTP 204 on DELETE),
 * subclasses set returnsSuccessData() to true; the base class synthesizes a
 * SuccessData DTO instead of invoking map() on an empty array.
 *
 * Subclasses still narrow map()'s return type to the concrete DTO so that
 * Tool::outputSchema() can reflect on it (Tool inspects the action's map()
 * return type to build the JSON output schema).
 */
abstract readonly class WriteApiAction extends ApiAction
{
    /**
     * When true, interpretResponse() returns a SuccessData DTO built from the
     * validated input rather than the response body. Override in subclasses
     * whose endpoints return HTTP 204 No Content.
     */
    protected function returnsSuccessData(): bool
    {
        return false;
    }

    /**
     * Build the SuccessData DTO used when returnsSuccessData() is true.
     *
     * Subclasses can override to attach the id of the record they just wrote
     * (typically pulled from the validated input for deletes).
     */
    protected function successData(ValidatedInput $input, Response $response): SuccessData
    {
        return new SuccessData([
            'success' => $response->successful(),
            'id' => null,
        ]);
    }

    protected function interpretResponse(ValidatedInput $input, Response $response): ArchivistDto|ArchivistDtoCollection
    {
        if ($this->returnsSuccessData()) {
            return $this->successData($input, $response);
        }

        return $this->map($response->fluent()->all());
    }
}
