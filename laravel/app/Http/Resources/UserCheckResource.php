<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCheckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_check_id' => $this->user_check_id,
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'user_id' => $this->user_id,
            'user' => new UserResource($this->User),
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->Asset),
            'reference_no' => $this->reference_no,
            'reference_date' => $this->reference_date,
            'note' => $this->note,
            'asset_checks' => UserAssetCheckResource::collection($this->UserAssetCheck),
            'user_attachments' => UserCheckAttachmentResource::collection($this->UserCheckAttachment),
            // 'asset_zone_id' => $this->asset_zone_id,
            // 'asset_zone' => new AssetZoneResource($this->AssetZone),
            'department_id' => $this->department_id,
            'department' => new DepartmentResource($this->Department)
        ];
    }
}
