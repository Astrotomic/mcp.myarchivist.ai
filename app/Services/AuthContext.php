<?php

namespace App\Services;

use Throwable;

/**
 * Lazily discovers the calling credential's scopes and API profile by
 * hitting GET /v1/users/me exactly once per request.
 *
 * Registered as a `scoped()` binding on the container so a single instance
 * is reused for the lifetime of an MCP JSON-RPC request. That means one
 * upstream call at most per `tools/list` (or per `tools/call`), and the
 * result is reused across every write tool's `shouldRegister()` check.
 */
class AuthContext
{
    /**
     * @var list<string>|null Cached scopes list; null before the first fetch.
     */
    private ?array $scopes = null;

    private ?string $apiProfile = null;

    private bool $fetched = false;

    /**
     * Set to true when the /users/me lookup failed. In that case we assume
     * the caller has full access — the API is the authoritative gate and
     * will surface a proper 401/403 on the actual tool call.
     */
    private bool $lookupFailed = false;

    public function __construct(private readonly ArchivistClient $client) {}

    /**
     * @return list<string>
     */
    public function scopes(): array
    {
        $this->ensureFetched();

        return $this->scopes ?? [];
    }

    public function apiProfile(): string
    {
        $this->ensureFetched();

        return $this->apiProfile ?? 'developer';
    }

    public function hasScope(string $scope): bool
    {
        return in_array($scope, $this->scopes(), true);
    }

    /**
     * Returns true when the caller is authorized to invoke write tools:
     *   - api_key / developer OAuth clients bypass agent scope checks
     *   - product OAuth clients rely on the API's product_write check
     *   - agent OAuth clients must hold the `agent_write` scope
     *   - on lookup failure, err on the side of showing everything and let
     *     the API return a clean 403 if the caller can't actually write
     */
    public function canWrite(): bool
    {
        $this->ensureFetched();

        if ($this->lookupFailed) {
            return true;
        }

        if ($this->apiProfile !== 'agent') {
            return true;
        }

        return $this->hasScope('agent_write');
    }

    private function ensureFetched(): void
    {
        if ($this->fetched) {
            return;
        }

        $this->fetched = true;

        try {
            $response = $this->client->get('/v1/users/me');
        } catch (Throwable) {
            $this->lookupFailed = true;

            return;
        }

        if (! $response->successful()) {
            $this->lookupFailed = true;

            return;
        }

        $scopes = $response->json('scopes');
        $this->scopes = is_array($scopes)
            ? array_values(array_filter(array_map(
                static fn (mixed $scope): string => is_string($scope) ? $scope : '',
                $scopes,
            ), static fn (string $scope): bool => $scope !== ''))
            : [];

        $apiProfile = $response->json('api_profile');
        $this->apiProfile = is_string($apiProfile) && $apiProfile !== '' ? $apiProfile : 'developer';
    }
}
