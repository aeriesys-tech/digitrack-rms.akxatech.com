<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetTemplateSpareResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_template_spare_id' => $this->asset_template_spare_id,
            'area_id' => $this->area_id,
            'area' => new AreaResource($this->Area),
            'spare_id' => $this->spare_id,
            'spare' => new SpareResource($this->Spare),
            'asset_template_id' => $this->asset_template_id,
            'asset_template' => new AssetTemplateResource($this->AssetTemplate),
            'template_zone_id' => $this->template_zone_id,
            'asset_zone' => new TemplateZoneResource($this->TemplateZone),
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'spare_type_id' => $this->spare_type_id,
            'spare_type' => new SpareTypeResource($this->SpareType),
            'status' => $this->deleted_at?false:true,
            'quantity' => $this->quantity,
            'asset_spare_attributes' => TemplateSpareValueResource::collection($this->TemplateSpareValue)
        ];
    }
}
