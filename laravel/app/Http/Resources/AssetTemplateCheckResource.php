<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetTemplateCheckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_template_check_id' => $this->asset_template_check_id,
            'area_id' => $this->area_id,
            'area' => new AreaResource($this->Area),
            'check_id' => $this->check_id,
            'check' => new CheckResource($this->Check),
            'asset_template_id' => $this->asset_template_id,
            'asset_template' => new AssetTemplateResource($this->AssetTemplate),
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'template_zone_id' => $this->template_zone_id,
            'asset_zone' => new TemplateZoneResource($this->TemplateZone),
            // 'status' => $this->deleted_at?false:true,
            'lcl' => $this->lcl,
            'ucl' => $this->ucl,
            'default_value' => $this->default_value
        ];
    }
}
