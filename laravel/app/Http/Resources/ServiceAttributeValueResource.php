<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceAttributeValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'service_attribute_value_id' => $this->service_attribute_value_id,
            'service_attribute_id' => $this->service_attribute_id,
            'service_attributes' => ServiceAttributeResource::collection($this->ServiceAttribute),
            'service_id' => $this->service_id,
            'field_value' => $this->field_value,
            'status' => $this->deleted_at?false:true
        ];
    }
}
