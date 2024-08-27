<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListParameterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'list_parameter_id' => $this->list_parameter_id,
            'list_parameter_name' => $this->list_parameter_name,
            'field_values' => $this->field_values,
            'status' => $this->deleted_at?false:true
        ];
    }
}
