<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetAccessoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_accessory_id' => $this->asset_accessory_id,
            'area_id' => $this->area_id,
            'area' => new AreaResource($this->Area),
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->Asset),
            'asset_zone_id' => $this->asset_zone_id,
            'asset_zone' => new AssetZoneResource($this->AssetZone),
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'accessory_type_id' => $this->accessory_type_id,
            'accessory_type' => new AccessoryTypeResource($this->AccessoryType),
            'accessory_name' => $this->accessory_name,
            'attachment' => $this->attachment ? config('app.asset_url').'assetAttachments/'.$this->attachment : null
        ];
    }
}
