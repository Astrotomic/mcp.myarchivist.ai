<?php

namespace App\Mcp\Prompts;

use App\Mcp\Resources\Sessions\SessionSummaryResource;
use App\Mcp\Resources\Sessions\SessionTranscriptResource;
use App\Mcp\Response;
use Laravel\Mcp\Request;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Prompt;
use Laravel\Mcp\Server\Prompts\Argument;

#[Name('Review TTRPG Session')]
#[Description('Review a TTRPG session with summary and transcript.')]
class ReviewSessionPrompt extends Prompt
{
    public function arguments(): array
    {
        return [
            new Argument('session_id', 'The ID of the session to review.', true),
            new Argument('language', 'The language to provide the review in.', false),
        ];
    }

    public function handle(Request $request): Response|ResponseFactory
    {
        $validated = $request->validate([
            'session_id' => ['required', 'string'],
            'language' => ['string'],
        ]);

        $session_id = $validated['session_id'];
        $language = $validated['language'] ?? 'en';

        return Response::make([
            Response::text('You are an expert TTRPG session reviewer. Use the provided session summary and transcript to create a detailed review. Analyze the narrative flow, character interactions, and key plot points. Offer insights into the session’s highlights and areas of interest.'),
            Response::embeddedResource(
                resource: app(SessionSummaryResource::class),
                arguments: ['session_id' => $session_id],
            ),
            Response::embeddedResource(
                resource: app(SessionTranscriptResource::class),
                arguments: ['session_id' => $session_id],
            ),
            Response::text("Please generate the complete session review in the following language: {$language}."),
        ]);
    }
}
