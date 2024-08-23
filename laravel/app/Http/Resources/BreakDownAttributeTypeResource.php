<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BreakDownAttributeTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'break_down_attribute_type_id' => $this->break_down_attribute_type_id,
            'break_down_attribute_id' => $this->break_down_attribute_id,
            'break_down_type_id' => $this->break_down_type_id,
            'break_down_type' => new BreakDownTypeResource($this->BreakDownType),
            'status' => $this->deleted_at?false:true
        ];
    }
}
