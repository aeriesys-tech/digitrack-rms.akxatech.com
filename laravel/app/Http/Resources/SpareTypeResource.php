<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpareTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'spare_type_id' => $this->spare_type_id,
            'spare_type_code' => $this->spare_type_code,
            'spare_type_name' => $this->spare_type_name,
            'status' => $this->deleted_at?false:true
        ];
    }
}
