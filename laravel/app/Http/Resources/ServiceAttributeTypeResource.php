<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceAttributeTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'service_attribute_type_id' => $this->service_attribute_type_id,
            'service_attribute_id' => $this->service_attribute_id,
            'service_type_id' => $this->service_type_id,
            'service_type' => new ServiceTypeResource($this->ServiceType),
            'status' => $this->deleted_at?false:true
        ];
    }
}
