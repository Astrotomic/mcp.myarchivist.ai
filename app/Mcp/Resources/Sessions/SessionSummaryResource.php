<?php

namespace App\Mcp\Resources\Sessions;

use App\Actions\Archivist\Sessions\GetSession;
use App\Mcp\Resources\Resource;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\MimeType;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Contracts\HasUriTemplate;
use Laravel\Mcp\Support\UriTemplate;

#[Name('Session Summary')]
#[Description('The summary of a TTRPG session.')]
#[MimeType('text/plain')]
class SessionSummaryResource extends Resource implements HasUriTemplate
{
    public function uriTemplate(): UriTemplate
    {
        return new UriTemplate('file://sessions/{session_id}/summary.txt');
    }

    public function handle(Request $request): Response
    {
        $session = GetSession::make()->execute([
            'session_id' => $request->string('session_id'),
        ]);

        if (empty($session->summary)) {
            return Response::error('Session summary not found.');
        }

        return Response::text($session->summary);
    }
}
