<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetServiceValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_service_value_id' => $this->asset_service_value_id,
            'asset_service_id' => $this->asset_service_id,
            'asset_id' => $this->asset_id,
            'service_id' => $this->service_id,
            'asset_zone_id' => $this->asset_zone_id,
            'service_attribute_id' => $this->service_attribute_id,
            'service_attributes' => ServiceAttributeResource::collection($this->ServiceAttribute),
            'field_value' => $this->field_value
        ];
    }
}
