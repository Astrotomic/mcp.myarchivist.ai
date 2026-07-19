<?php

namespace App\Actions\Archivist;

use App\Actions\Action;
use App\Actions\RulesToJsonSchema;
use App\Collections\ArchivistDtoCollection;
use App\Contracts\JsonSchemaable;
use App\Data\ArchivistDto;
use App\Services\ArchivistClient;
use Illuminate\Http\Client\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ValidatedInput;

abstract readonly class ApiAction extends Action implements JsonSchemaable
{
    /**
     * @return array<string, array<int, string>>
     */
    protected static function paginationRules(): array
    {
        return [
            'page' => ['nullable', 'integer', 'min:1'],
            'size' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function __construct(protected ArchivistClient $client) {}

    public function execute(array $params): ArchivistDto|LengthAwarePaginator
    {
        $validated = Validator::make($params, static::rules())->safe();

        $response = $this->request($validated);
        $data = $this->interpretResponse($validated, $response);

        if ($data instanceof ArchivistDtoCollection) {
            return new LengthAwarePaginator(
                items: $data,
                total: $response->fluent()->integer('total', $data->count()),
                perPage: $response->fluent()->integer('size', max($data->count(), 20)),
                currentPage: $response->fluent()->integer('page', $validated->integer('page', 1)),
            );
        }

        return $data;
    }

    abstract public static function rules(): array;

    abstract protected function request(ValidatedInput $input): Response;

    abstract protected function map(array $data): ArchivistDto|ArchivistDtoCollection;

    /**
     * Convert the raw HTTP response into a DTO (or DTO collection).
     *
     * The default implementation delegates to map() with the JSON body. Write
     * actions override this to accommodate empty (HTTP 204) responses.
     */
    protected function interpretResponse(ValidatedInput $input, Response $response): ArchivistDto|ArchivistDtoCollection
    {
        return $this->map($response->fluent()->all());
    }

    public static function toJsonSchema(): array
    {
        return RulesToJsonSchema::make()->execute(static::rules());
    }
}
