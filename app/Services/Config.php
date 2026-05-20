<?php

namespace App\Services;

use Illuminate\Support\Fluent;
use InvalidArgumentException;

final class Config extends Fluent
{
    public static function for(string $namespace): self
    {
        return new self($namespace);
    }

    public function __construct(string $namespace)
    {
        $config = config($namespace);

        if (! is_array($config)) {
            throw new InvalidArgumentException("Config {$namespace} is not an array.");
        }

        parent::__construct($config);
    }
}
