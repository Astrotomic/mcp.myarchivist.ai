<?php

namespace App\Actions\Archivist\Sessions;

use App\Actions\Archivist\WriteApiAction;
use App\Data\TextSessionData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;
use Illuminate\Support\ValidatedInput;

final readonly class CreateSessionFromNotes extends WriteApiAction
{
    /**
     * The API relays to the web app, which validates raw notes with an LLM before
     * creating the session, so allow well over the default 30s.
     */
    private const TIMEOUT_SECONDS = 90;

    public static function rules(): array
    {
        return [
            'campaign_id' => ['required', 'string'],
            'text' => ['required', 'string'],
            'format' => ['nullable', 'string', 'in:notes,transcript'],
            'title' => ['nullable', 'string', 'max:255'],
            'session_date' => ['nullable', 'string', 'date'],
            'idempotency_key' => ['required', 'string', 'max:200'],
        ];
    }

    protected function request(ValidatedInput $input): Response
    {
        return $this->client->post(
            '/v1/sessions/from-text',
            [
                'campaign_id' => $input->string('campaign_id')->toString(),
                'text' => $input->string('text')->toString(),
                'format' => $input->string('format', 'notes')->toString() ?: 'notes',
                'title' => $input->has('title') ? $input->input('title') : null,
                'session_date' => $input->has('session_date') ? $input->input('session_date') : null,
                'idempotency_key' => $input->string('idempotency_key')->toString(),
            ],
            self::TIMEOUT_SECONDS,
            ['X-Archivist-Client' => self::hostLabel()],
        );
    }

    protected function map(array $data): TextSessionData
    {
        return new TextSessionData($data);
    }

    /**
     * Best-effort label for the AI host making this call, for funnel analytics only.
     * Derived from the incoming MCP request's User-Agent; anything unrecognised is "other".
     */
    public static function hostLabel(?string $userAgent = null): string
    {
        $userAgent = Str::lower($userAgent ?? (string) request()->userAgent());

        return match (true) {
            Str::contains($userAgent, ['openai', 'chatgpt']) => 'chatgpt',
            Str::contains($userAgent, ['claude', 'anthropic']) => 'claude',
            default => 'other',
        };
    }
}
