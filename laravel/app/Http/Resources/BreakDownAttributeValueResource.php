<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BreakDownAttributeValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'break_down_attribute_value_id' => $this->break_down_attribute_value_id,
            'break_down_attribute_id' => $this->break_down_attribute_id,
            'break_down_attributes' => $this->BreakDownAttribute,
            'break_down_id' => $this->break_down_id,
            'field_value' => $this->field_value,
            'status' => $this->deleted_at?false:true
        ];
    }
}
