<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetAttributeValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_attribute_value_id' => $this->asset_attribute_value_id,
            'asset_attribute_id' => $this->asset_attribute_id,
            'asset_attributes' => $this->AssetAttribute,
            'asset_id' => $this->asset_id,
            'field_value' => $this->field_value,
            'status' => $this->deleted_at?false:true
        ];
    }
}
