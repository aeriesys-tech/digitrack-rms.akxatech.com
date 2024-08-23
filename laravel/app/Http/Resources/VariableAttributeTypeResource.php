<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariableAttributeTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'variable_attribute_type_id' => $this->variable_attribute_type_id,
            'variable_attribute_id' => $this->variable_attribute_id,
            'variable_type_id' => $this->variable_type_id,
            'variable_type' => new VariableTypeResource($this->VariableType),
            'status' => $this->deleted_at?false:true
        ];
    }
}
