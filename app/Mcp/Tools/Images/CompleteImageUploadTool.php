<?php

namespace App\Mcp\Tools\Images;

use App\Actions\Archivist\Images\CompleteImageUpload;
use App\Mcp\Tools\Tool;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsOpenWorld;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description(
    'STEP 2 of the direct-upload flow. Call after successfully PUTting bytes to the presigned '.
    '`upload_url` returned by `init_image_upload`. The API validates the object (size cap, image '.
    'signature) and runs the same NSFW moderation pipeline used by the product UI — flagged '.
    'uploads are deleted and the tool returns an error. When `attach` is true (default), the '.
    'entity\'s image field is set to the resulting `public_url`. Returns `{url, attached}`.'
)]
#[IsReadOnly(false)]
#[IsDestructive(true)]
#[IsIdempotent(false)]
#[IsOpenWorld(true)]
class CompleteImageUploadTool extends Tool
{
    protected function action(): CompleteImageUpload
    {
        return CompleteImageUpload::make();
    }
}
