<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariableAttributeValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'variable_attribute_value_id' => $this->variable_attribute_value_id,
            'variable_attribute_id' => $this->variable_attribute_id,
            'variable_attributes' => $this->VariableAttribute,
            'variable_id' => $this->variable_id,
            'field_value' => $this->field_value,
            'status' => $this->deleted_at?false:true
        ];
    }
}
