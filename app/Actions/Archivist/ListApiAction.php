<?php

namespace App\Actions\Archivist;

use App\Actions\RulesToJsonSchema;
use App\Collections\ArchivistDtoCollection;
use App\Data\ArchivistDto;
use Illuminate\Http\Client\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\LazyCollection;
use Illuminate\Validation\Rule;

abstract readonly class ListApiAction extends ApiAction
{
    private const int MAX_PAGE_SIZE = 100;

    private const int PAGE_FETCH_CONCURRENCY = 10;

    abstract protected static function listRules(): array;

    /**
     * @return array<string>
     */
    abstract protected static function filterableAttributes(): array;

    /**
     * @return array<string, mixed>
     */
    abstract protected function poolRequestForPage(array $params, int $page): array;

    /**
     * @return array<string>
     */
    protected static function sortableAttributes(): array
    {
        return static::filterableAttributes();
    }

    final public static function rules(): array
    {
        return array_merge(static::listRules(), [
            'filters' => ['nullable', 'array'],
            'filters.*' => ['required_with:filters', 'array'],
            'filters.*.field' => ['required', 'string', Rule::in(static::filterableAttributes())],
            'filters.*.operator' => ['required', Rule::enum(FilterOperator::class)],
            'filters.*.value' => ['required'],
            'sort' => ['nullable', 'array'],
            'sort.*' => ['required_with:sort', 'array'],
            'sort.*.field' => ['required', 'string', Rule::in(static::sortableAttributes())],
            'sort.*.direction' => ['required', Rule::enum(SortDirection::class)],
        ]);
    }

    public function execute(array $params): LengthAwarePaginator
    {
        if (! $this->shouldUseAllPagesFlow($params)) {
            /** @var LengthAwarePaginator $result */
            $result = parent::execute($params);

            return $result;
        }

        return $this->fetchAllAndProcess($params);
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

            $requests = array_map(
                fn (int $page): array => $this->poolRequestForPage($baseParams, $page),
                range(2, $lastPage),
            );

            $responses = $this->client->getPool($requests, self::PAGE_FETCH_CONCURRENCY);

            for ($page = 2; $page <= $lastPage; $page++) {
                $mapped = $this->mapResponse($responses[(string) $page]);
                yield from $mapped;
            }
        });
    }

    /**
     * @return ArchivistDtoCollection<int, ArchivistDto>
     */
    private function mapResponse(Response $response): ArchivistDtoCollection
    {
        $mapped = $this->map($response->fluent()->all());

        return $mapped instanceof ArchivistDtoCollection
            ? $mapped
            : new ArchivistDtoCollection([$mapped]);
    }

    protected function applyFilters(ArchivistDtoCollection $items, array $filters): ArchivistDtoCollection
    {
        return collect($filters)->reduce(function (ArchivistDtoCollection $filtered, array $filter): ArchivistDtoCollection {
            $field = $filter['field'];
            $operator = FilterOperator::from((string) $filter['operator']);
            $filterValue = $filter['value'];

            return ArchivistDtoCollection::make($filtered->filter(
                fn (ArchivistDto $dto): bool => $operator->matches($dto->get($field), $filterValue),
            )->values());
        }, $items);
    }

    protected function applySort(ArchivistDtoCollection $items, array $sort): ArchivistDtoCollection
    {
        if ($sort === []) {
            return $items;
        }

        $sortRules = collect($sort)
            ->map(fn (array $rule): array => [
                'field' => $rule['field'],
                'direction' => SortDirection::from((string) $rule['direction']),
            ])
            ->all();

        $sorted = $items->all();

        usort($sorted, function (ArchivistDto $left, ArchivistDto $right) use ($sortRules): int {
            foreach ($sortRules as $sortRule) {
                $comparison = $this->compareValues($left->get($sortRule['field']), $right->get($sortRule['field']));

                if ($comparison !== 0) {
                    return $sortRule['direction'] === SortDirection::Desc ? -$comparison : $comparison;
                }
            }

            return 0;
        });

        return ArchivistDtoCollection::make($sorted);
    }

    private function compareValues(mixed $leftValue, mixed $rightValue): int
    {
        if ($leftValue === $rightValue) {
            return 0;
        }

        if (is_numeric($leftValue) && is_numeric($rightValue)) {
            return (float) $leftValue <=> (float) $rightValue;
        }

        return strcmp(strtolower((string) $leftValue), strtolower((string) $rightValue));
    }

    public static function toJsonSchema(): array
    {
        return RulesToJsonSchema::make()->execute(static::rules());
    }
}
