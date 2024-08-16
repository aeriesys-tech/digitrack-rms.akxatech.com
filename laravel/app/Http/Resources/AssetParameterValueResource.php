<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetParameterValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_parameter_value_id' => $this->asset_parameter_value_id,
            'asset_parameter_id' => $this->asset_parameter_id,
            'asset_parameters' => $this->AssetParameter,
            'asset_id' => $this->asset_id,
            'field_value' => $this->field_value,
            'status' => $this->deleted_at?false:true
        ];
    }
}
