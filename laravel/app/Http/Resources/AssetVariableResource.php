<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetVariableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_variable_id' => $this->asset_variable_id,
            'area_id' => $this->area_id,
            'area' => new AreaResource($this->Area),
            'variable_id' => $this->variable_id,
            'variable' => new VariableResource($this->Variable),
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->Asset),
            // 'asset_zone_id' => $this->asset_zone_id,
            // 'asset_zone' => new AssetZoneResource($this->AssetZone),
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'variable_type_id' => $this->variable_type_id,
            'variable_type' => new VariableTypeResource($this->VariableType),
            'asset_variable_attributes' => AssetVariableValueResource::collection($this->AssetVariableValue)
        ];
    }
}
