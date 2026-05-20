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
    public function __construct(protected ArchivistClient $client) {}

    /**
     * @return ArchivistDto|LengthAwarePaginator<array-key, ArchivistDto>
     */
    public function execute(array $params): ArchivistDto|LengthAwarePaginator
    {
        $validated = Validator::make($params, static::rules())->safe();

        $response = $this->request($validated);
        $data = $this->map($response->json());

        if ($data instanceof ArchivistDtoCollection) {
            return new LengthAwarePaginator(
                items: $data,
                total: $response->json('total', $data->count()),
                perPage: $response->json('size', max($data->count(), 20)),
                currentPage: $response->json('page', $validated->integer('page', 1)),
            );
        }

        return $data;
    }

    abstract public static function rules(): array;

    abstract protected function request(ValidatedInput $input): Response;

    abstract protected function map(array $data): ArchivistDto|ArchivistDtoCollection;

    public static function toJsonSchema(): array
    {
        return RulesToJsonSchema::make()->execute(static::rules());
    }
}
