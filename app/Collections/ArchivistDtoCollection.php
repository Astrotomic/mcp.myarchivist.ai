<?php

namespace App\Collections;

use App\Data\ArchivistDto;
use Illuminate\Support\Collection;

/**
 * @template TKey of array-key
 * @template TValue of ArchivistDto
 *
 * @extends Collection<TKey, TValue>
 */
final class ArchivistDtoCollection extends Collection
{
    public function __construct($items = [])
    {
        parent::__construct($items);

        $this->ensure(ArchivistDto::class);
    }

    public function toArray(): array
    {
        return $this
            ->map(fn (ArchivistDto $dto) => $dto->toArray())
            ->all();
    }

    /**
     * @template TConcatKey of array-key
     * @template TConcatValue of ArchivistDto
     *
     * @param  iterable<TConcatKey, TConcatValue>  $source
     * @return self<array-key, TConcatValue|TValue>
     */
    public function concat($source): self
    {
        return parent::concat($source);
    }

    /**
     * @template TMapValue
     *
     * @param  callable(ArchivistDto, array-key): TMapValue  $callback
     * @return Collection<array-key, TMapValue>
     */
    public function map(callable $callback): Collection
    {
        return $this
            ->toBase()
            ->map($callback);
    }
}
