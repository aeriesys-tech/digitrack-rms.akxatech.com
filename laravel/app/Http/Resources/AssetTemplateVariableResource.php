<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetTemplateVariableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_template_variable_id' => $this->asset_template_variable_id,
            'area_id' => $this->area_id,
            'area' => new AreaResource($this->Area),
            'variable_id' => $this->variable_id,
            'variable' => new VariableResource($this->Variable),
            'asset_template_id' => $this->asset_template_id,
            'asset_template' => new AssetTemplateResource($this->AssetTemplate),
            'template_zone_id' => $this->template_zone_id,
            'asset_zone' => new TemplateZoneResource($this->TemplateZone),
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'variable_type_id' => $this->variable_type_id,
            'variable_type' => new VariableTypeResource($this->VariableType),
            'asset_variable_attributes' => TemplateVariableValueResource::collection($this->TemplateVariableValue)
        ];
    }
}
