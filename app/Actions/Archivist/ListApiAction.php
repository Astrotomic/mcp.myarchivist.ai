<?php

namespace App\Actions\Archivist;

use App\Collections\ArchivistDtoCollection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract readonly class ListApiAction extends ApiAction
{
    abstract protected static function listRules(): array;

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
        $filters = $params['filters'] ?? [];
        $sort = $params['sort'] ?? [];

        if (is_array($filters) && $filters !== [] || is_array($sort) && $sort !== []) {
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

        unset($params['filters'], $params['sort'], $params['page']);
        $params['size'] = 100;

        $currentPage = 1;
        $pages = 1;
        $items = new ArchivistDtoCollection;

        do {
            $response = parent::execute(array_merge($params, ['page' => $currentPage]));

            $items = $items->concat($response->getCollection());
            $pages = max($pages, $response->lastPage());
            $currentPage++;
        } while ($currentPage <= $pages);

        $processed = $this->applySort($this->applyFilters($items, $filters), $sort)->values();

        return new LengthAwarePaginator(
            items: $processed,
            total: $processed->count(),
            perPage: max($processed->count(), 1),
            currentPage: 1,
        );
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

            $items = ArchivistDtoCollection::make($items->filter(function ($dto) use ($field, $operator, $filterValue): bool {
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

        usort($sorted, function ($left, $right) use ($sort): int {
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

                if (is_numeric($leftValue) && is_numeric($rightValue)) {
                    $comparison = (float) $leftValue <=> (float) $rightValue;
                } else {
                    $comparison = strcmp(strtolower((string) $leftValue), strtolower((string) $rightValue));
                }

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

        $schema['required'] = array_values(array_filter($schema['required'] ?? [], fn (string $field): bool => ! in_array($field, ['filters', 'sort'], true)));

        return $schema;
    }
}
