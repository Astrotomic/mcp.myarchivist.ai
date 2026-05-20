<?php

namespace App\Mcp\Resources;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Resource as BaseResource;

abstract class Resource extends BaseResource
{
    abstract public function handle(Request $request): Response;
}
