<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetVariableValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_variable_value_id' => $this->asset_variable_value_id,
            'asset_variable_id' => $this->asset_variable_id,
            'asset_id' => $this->asset_id,
            'variable_id' => $this->variable_id,
            // 'asset_zone_id' => $this->asset_zone_id,
            'variable_attribute_id' => $this->variable_attribute_id,
            'variable_attributes' => VariableAttributeResource::collection($this->VariableAttribute),
            'field_value' => $this->field_value
        ];
    }
}
