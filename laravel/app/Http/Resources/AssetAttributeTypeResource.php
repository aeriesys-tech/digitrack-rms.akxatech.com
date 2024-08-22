<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetAttributeTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_attribute_type_id' => $this->asset_attribute_type_id,
            'asset_attribute_id' => $this->asset_attribute_id,
            // 'asset_parameter' => new AssetParameterResource($this->AssetParameter),
            'asset_type_id' => $this->asset_type_id,
            'asset_type' => new AssetTypeResource($this->AssetType),
            'status' => $this->deleted_at?false:true
        ];
    }
}
