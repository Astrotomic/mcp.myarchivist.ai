<?php

namespace App\Actions\Archivist;

enum FilterOperator: string
{
    case Eq = 'eq';
    case Neq = 'neq';
    case Gt = 'gt';
    case Gte = 'gte';
    case Lt = 'lt';
    case Lte = 'lte';
    case Contains = 'contains';
    case In = 'in';

    public function matches(mixed $value, mixed $filterValue): bool
    {
        return match ($this) {
            self::Eq => is_numeric($value) && is_numeric($filterValue)
                ? (float) $value === (float) $filterValue
                : $value === $filterValue,
            self::Neq => is_numeric($value) && is_numeric($filterValue)
                ? (float) $value !== (float) $filterValue
                : $value !== $filterValue,
            self::Gt => is_numeric($value) && is_numeric($filterValue) && (float) $value > (float) $filterValue,
            self::Gte => is_numeric($value) && is_numeric($filterValue) && (float) $value >= (float) $filterValue,
            self::Lt => is_numeric($value) && is_numeric($filterValue) && (float) $value < (float) $filterValue,
            self::Lte => is_numeric($value) && is_numeric($filterValue) && (float) $value <= (float) $filterValue,
            self::Contains => str_contains(strtolower((string) $value), strtolower((string) $filterValue)),
            self::In => in_array($value, (array) $filterValue, true),
        };
    }
}
