<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return[
            'area_id' => $this->area_id,
            'area_code' => $this->area_code,
            'area_name' => $this->area_name,
            'status' => $this->deleted_at?false:true
        ];
    }
}
