<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetParameterTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_parameter_type_id' => $this->asset_parameter_type_id,
            'asset_parameter_id' => $this->asset_parameter_id,
            // 'asset_parameter' => new AssetParameterResource($this->AssetParameter),
            'asset_type_id' => $this->asset_type_id,
            'asset_type' => new AssetTypeResource($this->AssetType),
            'status' => $this->deleted_at?false:true
        ];
    }
}
