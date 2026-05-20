<?php

namespace App\Collections;

use App\Data\ArchivistDto;
use Illuminate\Support\Collection;

/**
 * @template TKey of array-key
 * @template TModel of ArchivistDto
 *
 * @extends Collection<TKey, TModel>
 */
class ArchivistDtoCollection extends Collection
{
    public function __construct($items = [])
    {
        parent::__construct($items);

        $this->ensure(ArchivistDto::class);
    }
}
