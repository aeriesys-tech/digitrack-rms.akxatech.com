<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetDataSourceValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_data_source_value_id' => $this->asset_data_source_value_id,
            'asset_data_source_id' => $this->asset_data_source_id,
            'asset_id' => $this->asset_id,
            'data_source_id' => $this->data_source_id,
            // 'asset_zone_id' => $this->asset_zone_id,
            'data_source_attribute_id' => $this->data_source_attribute_id,
            'data_source_attributes' => DataSourceAttributeResource::collection($this->DataSourceAttribute),
            'field_value' => $this->field_value
        ];
    }
}
