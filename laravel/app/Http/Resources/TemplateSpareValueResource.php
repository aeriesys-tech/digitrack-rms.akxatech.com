<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateSpareValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'template_spare_value_id' => $this->template_spare_value_id,
            'asset_template_spare_id' => $this->asset_template_spare_id,
            'asset_template_id' => $this->asset_template_id,
            'spare_id' => $this->spare_id,
            'template_zone_id' => $this->template_zone_id,
            'spare_attribute_id' => $this->spare_attribute_id,
            'spare_attributes' => SpareAttributeResource::collection($this->SpareAttribute),
            'field_value' => $this->field_value
        ];
    }
}
