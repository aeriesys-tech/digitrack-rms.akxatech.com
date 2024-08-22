<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpareAttributeValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'spare_attribute_value_id' => $this->spare_attribute_value_id,
            'spare_attribute_id' => $this->spare_attribute_id,
            'spare_attributes' => $this->SpareAttribute,
            'spare_id' => $this->spare_id,
            'field_value' => $this->field_value,
            'status' => $this->deleted_at?false:true
        ];
    }
}