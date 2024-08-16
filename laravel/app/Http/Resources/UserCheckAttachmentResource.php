<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;

class UserCheckAttachmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_check_attachment_id' => $this->user_check_attachment_id,
            'user_check_id' => $this->user_check_id,
            'attachments' => $this->attachments ? Storage::disk('s3')->url($this->attachments) : null,
            'file_name' => $this->attachments
        ];
    }
}
