<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetSpareValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_spare_value_id' => $this->asset_spare_value_id,
            'asset_spare_id' => $this->asset_spare_id,
            'asset_id' => $this->asset_id,
            'spare_id' => $this->spare_id,
            // 'asset_zone_id' => $this->asset_zone_id,
            'spare_attribute_id' => $this->spare_attribute_id,
            'spare_attributes' => SpareAttributeResource::collection($this->SpareAttribute),
            'field_value' => $this->field_value
        ];
    }
}
