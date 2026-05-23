<?php

namespace App\Actions\Archivist;

use App\Collections\ArchivistDtoCollection;
use App\Data\ArchivistDto;
use Illuminate\Http\Client\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\LazyCollection;

abstract readonly class ListApiAction extends ApiAction
{
    private const int MAX_PAGE_SIZE = 100;

    private const int PAGE_FETCH_CONCURRENCY = 10;

    abstract protected static function listRules(): array;

    /**
     * @return array<string, mixed>
     */
    abstract protected function poolRequestForPage(array $params, int $page): array;

    final public static function rules(): array
    {
        return array_merge(static::listRules(), [
            'filters' => ['nullable', 'array'],
            'filters.*.field' => ['required_with:filters.*', 'string'],
            'filters.*.operator' => ['required_with:filters.*', 'string', 'in:eq,neq,gt,gte,lt,lte,contains,in'],
            'filters.*.value' => ['required_with:filters.*'],
            'sort' => ['nullable', 'array'],
            'sort.*.field' => ['required_with:sort.*', 'string'],
            'sort.*.direction' => ['required_with:sort.*', 'string', 'in:asc,desc'],
        ]);
    }

    public function execute(array $params): LengthAwarePaginator
    {
        if ($this->shouldUseAllPagesFlow($params)) {
            return $this->fetchAllAndProcess($params);
        }

        /** @var LengthAwarePaginator $result */
        $result = parent::execute($params);

        return $result;
    }

    protected function fetchAllAndProcess(array $params): LengthAwarePaginator
    {
        $filters = is_array($params['filters'] ?? null) ? $params['filters'] : [];
        $sort = is_array($params['sort'] ?? null) ? $params['sort'] : [];

        $baseParams = $this->normalizedFetchParams($params);

        $allItems = ArchivistDtoCollection::make($this->buildAllPagesLazyCollection($baseParams)->all());
        $processed = $this->applySort($this->applyFilters($allItems, $filters), $sort)->values();

        return new LengthAwarePaginator(
            items: $processed,
            total: $processed->count(),
            perPage: max($processed->count(), 1),
            currentPage: 1,
        );
    }

    private function shouldUseAllPagesFlow(array $params): bool
    {
        $filters = $params['filters'] ?? [];
        $sort = $params['sort'] ?? [];

        return (is_array($filters) && $filters !== []) || (is_array($sort) && $sort !== []);
    }

    private function normalizedFetchParams(array $params): array
    {
        unset($params['filters'], $params['sort'], $params['page']);
        $params['size'] = self::MAX_PAGE_SIZE;

        return $params;
    }

    /**
     * @return LazyCollection<int, ArchivistDto>
     */
    private function buildAllPagesLazyCollection(array $baseParams): LazyCollection
    {
        return LazyCollection::make(function () use ($baseParams): \Generator {
            $firstPage = parent::execute(array_merge($baseParams, ['page' => 1]));
            yield from $firstPage->getCollection();

            $lastPage = $firstPage->lastPage();
            if ($lastPage <= 1) {
                return;
            }

            foreach (array_chunk(range(2, $lastPage), self::PAGE_FETCH_CONCURRENCY) as $pageChunk) {
                $responses = $this->client->getPool(
                    requests: array_map(fn (int $page): array => $this->poolRequestForPage($baseParams, $page), $pageChunk),
                    concurrency: self::PAGE_FETCH_CONCURRENCY,
                );

                foreach ($pageChunk as $page) {
                    $mapped = $this->mapResponse($responses[(string) $page]);
                    yield from $mapped;
                }
            }
        });
    }

    /**
     * @return ArchivistDtoCollection<int, ArchivistDto>
     */
    private function mapResponse(Response $response): ArchivistDtoCollection
    {
        $mapped = $this->map($response->fluent()->all());

        return $mapped instanceof ArchivistDtoCollection ? $mapped : new ArchivistDtoCollection([$mapped]);
    }

    protected function applyFilters(ArchivistDtoCollection $items, array $filters): ArchivistDtoCollection
    {
        foreach ($filters as $filter) {
            $field = $filter['field'] ?? null;
            $operator = $filter['operator'] ?? null;
            $filterValue = $filter['value'] ?? null;

            if (! is_string($field) || ! is_string($operator)) {
                continue;
            }

            $items = ArchivistDtoCollection::make($items->filter(function (ArchivistDto $dto) use ($field, $operator, $filterValue): bool {
                $value = $dto->get($field);

                return match ($operator) {
                    'eq' => is_numeric($value) && is_numeric($filterValue)
                        ? (float) $value === (float) $filterValue
                        : $value === $filterValue,
                    'neq' => is_numeric($value) && is_numeric($filterValue)
                        ? (float) $value !== (float) $filterValue
                        : $value !== $filterValue,
                    'gt' => is_numeric($value) && is_numeric($filterValue) && (float) $value > (float) $filterValue,
                    'gte' => is_numeric($value) && is_numeric($filterValue) && (float) $value >= (float) $filterValue,
                    'lt' => is_numeric($value) && is_numeric($filterValue) && (float) $value < (float) $filterValue,
                    'lte' => is_numeric($value) && is_numeric($filterValue) && (float) $value <= (float) $filterValue,
                    'contains' => str_contains(strtolower((string) $value), strtolower((string) $filterValue)),
                    'in' => in_array($value, (array) $filterValue, true),
                    default => true,
                };
            })->values());
        }

        return $items;
    }

    protected function applySort(ArchivistDtoCollection $items, array $sort): ArchivistDtoCollection
    {
        if ($sort === []) {
            return $items;
        }

        $sorted = $items->all();

        usort($sorted, function (ArchivistDto $left, ArchivistDto $right) use ($sort): int {
            foreach ($sort as $sortRule) {
                $field = $sortRule['field'] ?? null;
                $direction = strtolower((string) ($sortRule['direction'] ?? 'asc'));

                if (! is_string($field)) {
                    continue;
                }

                $leftValue = $left->get($field);
                $rightValue = $right->get($field);

                if ($leftValue === $rightValue) {
                    continue;
                }

                $comparison = is_numeric($leftValue) && is_numeric($rightValue)
                    ? (float) $leftValue <=> (float) $rightValue
                    : strcmp(strtolower((string) $leftValue), strtolower((string) $rightValue));

                return $direction === 'desc' ? -$comparison : $comparison;
            }

            return 0;
        });

        return ArchivistDtoCollection::make($sorted);
    }

    public static function toJsonSchema(): array
    {
        $schema = parent::toJsonSchema();

        $schema['properties']['filters'] = [
            'type' => 'array',
            'items' => [
                'type' => 'object',
                'required' => ['field', 'operator', 'value'],
                'properties' => [
                    'field' => ['type' => 'string'],
                    'operator' => ['type' => 'string', 'enum' => ['eq', 'neq', 'gt', 'gte', 'lt', 'lte', 'contains', 'in']],
                    'value' => new \stdClass,
                ],
            ],
        ];

        $schema['properties']['sort'] = [
            'type' => 'array',
            'items' => [
                'type' => 'object',
                'required' => ['field', 'direction'],
                'properties' => [
                    'field' => ['type' => 'string'],
                    'direction' => ['type' => 'string', 'enum' => ['asc', 'desc']],
                ],
            ],
        ];

        $schema['required'] = array_values(array_filter(
            $schema['required'] ?? [],
            fn (string $field): bool => ! in_array($field, ['filters', 'sort'], true),
        ));

        return $schema;
    }
}
