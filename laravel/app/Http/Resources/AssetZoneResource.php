<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetZoneResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_zone_id' => $this->asset_zone_id,
            'asset_id' => $this->asset_id,
            // 'asset' => new AssetResource($this->Asset),
            'zone_name' => $this->zone_name,
            'status' => $this->deleted_at?false:true
        ];
    }
}
