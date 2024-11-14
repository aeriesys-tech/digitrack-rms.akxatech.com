<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetTemplateAccessoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_template_accessory_id' => $this->asset_template_accessory_id,
            'area_id' => $this->area_id,
            'area' => new AreaResource($this->Area),
            'asset_template_id' => $this->asset_template_id,
            'asset_template' => new AssetTemplateResource($this->AssetTemplate),
            'template_zone_id' => $this->template_zone_id,
            'asset_zone' => new TemplateZoneResource($this->TemplateZone),
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'accessory_type_id' => $this->accessory_type_id,
            'accessory_type' => new AccessoryTypeResource($this->AccessoryType),
            'accessory_name' => $this->accessory_name,
            'attachment' => $this->attachment ? config('app.asset_url').'assetAttachments/'.$this->attachment : null
        ];
    }
}
