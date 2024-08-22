<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BreakDownTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'break_down_type_id' => $this->break_down_type_id,
            'break_down_type_code' => $this->break_down_type_code,
            'break_down_type_name' => $this->break_down_type_name,
            'status' => $this->deleted_at?false:true
        ];
    }
}