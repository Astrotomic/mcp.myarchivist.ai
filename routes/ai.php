<?php

use App\Http\Middleware\ArchivistPassthroughMiddleware;
use App\Http\Middleware\McpCorsMiddleware;
use App\Mcp\Servers\ArchivistServer;
use Laravel\Mcp\Facades\Mcp;

/*
 * Web (HTTP/SSE) transport — Bearer token is extracted and forwarded to the
 * Archivist API via ArchivistPassthroughMiddleware.
 */
Mcp::web('/mcp', ArchivistServer::class)
    ->name('mcp')
    ->middleware([
        McpCorsMiddleware::class,
        ArchivistPassthroughMiddleware::class,
    ]);

/*
 * Local (stdio) transport — for editor integrations such as Cursor.
 * Uses ARCHIVIST_API_KEY from the environment as the API key.
 */
Mcp::local('archivist', ArchivistServer::class);
