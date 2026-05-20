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

    public function execute(array $params): ArchivistDto|LengthAwarePaginator
    {
        $validated = Validator::make($params, static::rules())->safe();

        $response = $this->request($validated);
        $data = $this->map($response->fluent()->all());

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

    public static function toJsonSchema(): array
    {
        return RulesToJsonSchema::make()->execute(static::rules());
    }
}
