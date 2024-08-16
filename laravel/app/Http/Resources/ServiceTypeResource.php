<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'service_type_id' => $this->service_type_id,
            'service_type_code' => $this->service_type_code,
            'service_type_name' => $this->service_type_name,
            'status' => $this->deleted_at?false:true
        ];
    }
}
