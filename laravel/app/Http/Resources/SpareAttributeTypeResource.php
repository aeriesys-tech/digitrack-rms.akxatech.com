<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpareAttributeTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'spare_attribute_type_id' => $this->spare_attribute_type_id,
            'spare_attribute_id' => $this->spare_attribute_id,
            'spare_type_id' => $this->spare_type_id,
            'spare_type' => new SpareTypeResource($this->SpareType),
            'status' => $this->deleted_at?false:true
        ];
    }
}
