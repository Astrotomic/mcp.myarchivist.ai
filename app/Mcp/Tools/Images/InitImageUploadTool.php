<?php

namespace App\Mcp\Tools\Images;

use App\Actions\Archivist\Images\InitImageUpload;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'STEP 1 of the direct-upload flow. Ask the API to reserve an object key for the entity and '.
    'presign an S3 PUT URL. `content_type` must be image/* (jpeg, png, gif, webp, avif, heic, heif). '.
    'Returns `{object_key, upload_url, public_url, expires_in_seconds}`. AFTER calling this, the '.
    'client MUST issue an HTTP `PUT` to `upload_url` with the raw image bytes and the same '.
    '`Content-Type` header before it expires (typically ~5 minutes). Once the PUT is complete, '.
    'call `complete_image_upload` to finalize (moderate and optionally attach). Agents that cannot '.
    'perform arbitrary HTTP PUTs should ask a human collaborator to do the PUT step, or use '.
    '`generate_image` instead for server-side generation.'
)]
#[IsReadOnly(false)]
#[IsDestructive(false)]
#[IsIdempotent(false)]
#[IsOpenWorld(false)]
class InitImageUploadTool extends Tool
{
    protected function action(): InitImageUpload
    {
        return InitImageUpload::make();
    }
}
