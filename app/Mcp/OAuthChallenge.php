<?php

namespace App\Mcp;

final class OAuthChallenge
{
    public const CONNECT_URL = 'https://www.myarchivist.ai/mcp';

    public const CONNECT_MESSAGE = 'Connect Archivist AI in your MCP client and sign in, then retry. Setup: '.self::CONNECT_URL;

    public const RECONNECT_MESSAGE = 'Reconnect Archivist AI in your MCP client and sign in again, then retry. Setup: '.self::CONNECT_URL;

    public static function header(bool $invalidToken = false): string
    {
        $metadata = route('mcp.oauth.protected-resource.nested', ['path' => 'mcp']);
        $challenge = sprintf('Bearer realm="mcp", resource_metadata="%s"', $metadata);

        if ($invalidToken) {
            $challenge .= ', error="invalid_token", error_description="Reconnect Archivist AI and sign in again"';
        }

        return $challenge;
    }
}
