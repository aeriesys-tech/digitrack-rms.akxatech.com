<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessoryTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return[
            'accessory_type_id' => $this->accessory_type_id,
            'accessory_type_code' => $this->accessory_type_code,
            'accessory_type_name'=>$this->accessory_type_name,
            'status' => $this->deleted_at?false:true
        ];
    }
}
