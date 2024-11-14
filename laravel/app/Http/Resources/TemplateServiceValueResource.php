<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateServiceValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'template_service_value_id' => $this->template_service_value_id,
            'asset_template_service_id' => $this->asset_template_service_id,
            'asset_template_id' => $this->asset_template_id,
            'service_id' => $this->service_id,
            'template_zone_id' => $this->template_zone_id,
            'service_attribute_id' => $this->service_attribute_id,
            'service_attributes' => ServiceAttributeResource::collection($this->ServiceAttribute),
            'field_value' => $this->field_value
        ];
    }
}
