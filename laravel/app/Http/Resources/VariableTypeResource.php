<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariableTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'variable_type_id' => $this->variable_type_id,
            'variable_type_code' => $this->variable_type_code,
            'variable_type_name' => $this->variable_type_name,
            'status' => $this->deleted_at?false:true
        ];
    }
}
