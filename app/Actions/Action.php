<?php

namespace App\Actions;

abstract readonly class Action
{
    public static function make(): static
    {
        return app(static::class);
    }
}
